<?php

namespace App\Conversations;

use App\Enums\ApplicationType;
use App\Enums\SymptomsType;
use App\Model\Person\Person;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Log;

class CoreConversation extends Conversation
{
    protected $firstFeelings;
    protected $confirmFeelings;
    protected $symptoms = [];
    protected $confirmSymptoms;
    protected $levelFever;
    protected $confirmStillHaveSymptoms;
    protected $haveHearOrLungProblem;
    protected $haveDiabetes;
    protected $isPregnant;
    protected $name;
    protected $age;
    protected $cep;
    protected $cpf;
    protected $cellNumber;
    protected $acceptedTerms;
    protected $protocol;
    protected $personName;

    public function run()
    {
        //
    }

    private function getPersonByPhone()
    {
        $phone = $this->getFormattedPhone();

        // Como na base pode ter telefones com o 9 adicional
        // adicionado um cláusula OR com o novo padrão
        $phoneNewPattern = substr($phone, 0, 2) .'9'. substr($phone, 2);

        // vai buscar por 4899887766 ou 48999887766
        $person = Person::where('phone', $phone)->orWhere('phone', $phoneNewPattern)->first();

        $this->personName = $person->name ?? null;

        return $person;
    }

    private function getFormattedPhone()
    {
        $phone = str_replace('@c.us', '', $this->bot->getUser()->getId());
        // TODO: está removendo os 2 primeiros numeros (codigo internacional)
        // tem que salvar na base esse codigo, dai pode ser removido essa parte abaixo
        $phone = substr($phone, 2);

        return $phone;
    }

    public function saveConfirmedSymptoms()
    {
        $person = $this->getPersonByPhone();

        if (!$person) {
            return $this->sayPersonNotFound();
        }

        if ($person->monitoringPersonToday()) {
            return $this->sayPersonHasMonitoredToday();
        }

        $person->monitoringsPerson()->create([
            'application' => ApplicationType::WHATSAPP,
            'notes' => 'Monitorado por robô',
            'symptoms' => json_encode(['monitored' => $this->symptoms])
        ]);

        $this->checkConfirmedSymptomsRecommendation();
    }

    public function saveWithoutSymptoms()
    {
        $person = $this->getPersonByPhone();

        if (!$person) {
            return $this->sayPersonNotFound();
        }

        if ($person->monitoringPersonToday()) {
            return $this->sayPersonHasMonitoredToday();
        }

        $person->monitoringsPerson()->create([
            'application' => ApplicationType::WHATSAPP,
            'notes' => 'Monitorado por robô. Colaborador não apresenta sintomas.'
        ]);

        $this->sayAllGood();
    }

    public function saveFirstConversation(Person $person)
    {
        $person->bot_optin = true;
        $person->save();
    }

    public function checkPersonExists()
    {
        $person = $this->getPersonByPhone();

        if (!$person) {
            return $this->sayPersonNotFound();
        }

        $this->saveFirstConversation($person);

        if ($person->monitoringPersonToday()) {
            return $this->sayPersonHasMonitoredToday();
        }

        return $this->askFeelings();
    }

    public function sayWrongAnswer()
    {
        $this->say('Resposta inválida! Por favor, responda corretamente.');
    }

    public function sayPersonHasMonitoredToday()
    {
        $this->say("Olá *{$this->personName}*! Verifiquei que você já foi monitorado(a) hoje. Por favor, entre em contato com o seu superior imediado para verificar a sua situação.");
    }

    public function sayPersonNotFound()
    {
        $this->say('Olá! Não encontrei o seu número de telefone em nossa base de dados. Por favor, entre em contato com o seu superior imediato para atualização do seu cadastro.');
    }

    public function askInitialContact()
    {
        $message = 'Olá, sou o assistente virtual que monitora sua saúde diariamente para combatermos o Coronavírus. Vou fazer algumas perguntas para atualizar o seu cadastro.';
        $this->say($message);

        $this->askName();
    }

    public function askName()
    {
        $question = 'Qual seu nome?';

        $this->ask($question, function(Answer $answer) {
            $this->name = $answer->getText();

            $this->askAge();
        });
    }

    public function askAge()
    {
        $question = 'Qual sua idade?';

        $this->ask($question, function(Answer $answer) {
            preg_match_all('!\d+!', $answer->getText(), $match);

            $match = array_map('intval', $match[0]);

            if (count($match) != 1) {
                $this->sayWrongAnswer();
                return $this->askAge();
            }

            $this->age = $match[0];

            $this->askCEP();
        });
    }

    public function askCEP()
    {
        $question = 'Qual seu CEP? Responda no formato 88888-888';

        $this->ask($question, function(Answer $answer) {

            if (preg_match("/^[0-9]{5}-[0-9]{3}$/", $answer->getText()) == false) {
                $this->sayWrongAnswer();
                return $this->askCEP();
            }

            $this->cep = $answer->getText();

            $this->askCPF();
        });
    }

    public function askCPF()
    {
        $question = 'Qual seu CPF? Responda no formato 888.888.888-88';

        $this->ask($question, function(Answer $answer) {

            if (preg_match("/^[0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2}$/", $answer->getText()) == false) {
                $this->sayWrongAnswer();
                return $this->askCPF();
            }

            $this->cpf = $answer->getText();

            $this->askCellNumber();
        });
    }

    public function askCellNumber()
    {
        $question = 'Qual o seu telefone celular? Responda no formato 48 99999-9999';

        $this->ask($question, function(Answer $answer) {

            if (preg_match("/^[0-9]{2} [0-9]{5}-[0-9]{4}$/", $answer->getText()) == false) {
                $this->sayWrongAnswer();
                return $this->askCellNumber();
            }

            $this->cellNumber = $answer->getText();

            $this->askAcceptTerms();
        });
    }

    public function askAcceptTerms()
    {
        $question = 'A decisão de buscar ou não a ajuda médica, independente das informações obtidas aqui na ferramenta, é de conta e risco do usuário.
Favor ler os Termos e Condições de Uso acessando esse link: http://www.coronadados.com.br/termo
Você aceita? Responda *Sim* ou *Não*.';

        $this->ask($question, [
            [
                'pattern' => 'sim',
                'callback' => function () {
                    $this->acceptedTerms = 'sim';
                    $this->askFeelings();
                }
            ],
            [
                'pattern' => 'nao|não',
                'callback' => function () {
                    $this->acceptedTerms = 'nao';
                }
            ],
            [
                'pattern' => '',
                'callback' => function () {
                    $this->sayWrongAnswer();
                    $this->askAcceptTerms();
                }
            ]
        ]);
    }

    public function askFeelings()
    {
        $question = "Olá *{$this->personName}*! Eu sou o assistente virtual que monitora sua saúde diariamente para combatermos o Coronavírus.
Como você está se sentindo hoje?
Responda somente *Bem* ou *Mal*.";

        $this->ask($question, [
            [
                'pattern' => 'bem',
                'callback' => function () {
                    $this->firstFeelings = 'bem';
                    $this->askConfirmGoodFeelings();
                }
            ],
            [
                'pattern' => 'mal',
                'callback' => function () {
                    $this->firstFeelings = 'mal';
                    $this->askConfirmBadFeelings();
                }
            ],
            [
                'pattern' => '',
                'callback' => function () {
                    $this->sayWrongAnswer();
                    $this->askFeelings();
                }
            ]
        ]);
    }

    public function askConfirmGoodFeelings()
    {
        $question = 'Você está sem nenhum desses sintomas:
febre, tosse seca, dor no corpo, dificuldade para respirar, dor de garganta, cansaço, falta de paladar, congestão nasal, coriza, diarreia, ou mal estar geral.

-Se *está se sentindo BEM* e sem esses sintomas, responda com *SIM*. Se você estiver sentindo algum desses sintomas e então NÃO está BEM responda com *NÃO*.';

        $this->ask($question, [
            [
                'pattern' => 'sim',
                'callback' => function () {
                    $this->confirmFeelings = 'sim';
                    $this->saveWithoutSymptoms();
                }
            ],
            [
                'pattern' => 'nao|não',
                'callback' => function () {
                    $this->confirmFeelings = 'nao';
                    $this->askConfirmBadFeelings();
                }
            ],
            [
                'pattern' => '',
                'callback' => function () {
                    $this->sayWrongAnswer();
                    $this->askConfirmGoodFeelings();
                }
            ]
        ]);
    }

    public function askConfirmBadFeelings()
    {
        $question = 'Quais desses sintomas vocês está sentindo?
*1*  Febre
*2*  Tosse seca
*3*  Cansaço
*4*  Dor no corpo
*5*  Dor de Garganta
*6*  Congestão nasal
*7*  Coriza
*8*  Diarreia
*9*  Sem paladar
*10* Falta de ar/Dificuldade para respirar

Responda os números correspondentes ao seus sintomas.
(Por exemplo: 1, 3 e 7.)';

        $this->ask($question, function (Answer $answer) {

            preg_match_all('!\d+!', $answer->getText(), $match);

            $this->symptoms = collect($match[0])->unique()->map(function($id) {
                return SymptomsType::getValueById($id);
            });

            // se não foi informado nenhum sintoma
            $emptySymptoms = !count($this->symptoms);

            // se foi informado um número inválido
            $invalidSymptoms = $this->symptoms->diff(SymptomsType::getValues());

            if ($emptySymptoms || !$invalidSymptoms->isEmpty()) {
                $this->sayWrongAnswer();
                return $this->askConfirmBadFeelings(true);
            }

            $this->askConfirmSymptoms();
        });
    }

    public function askConfirmSymptoms()
    {
        $question = 'Atenção, a falsa declaração de sintomas é crime e compromete a sua saúde e a da população.
Você confirma o envio dessa informação?
Responda *Sim* ou *Não*';

        $this->ask($question, [
            [
                'pattern' => 'sim',
                'callback' => function () {
                    $this->confirmSymptoms = 'sim';
                    $this->saveConfirmedSymptoms();
                }
            ],
            [
                'pattern' => 'nao|não',
                'callback' => function () {
                    $this->confirmSymptoms = 'nao';
                    $this->askFeelings();
                }
            ],
            [
                'pattern' => '',
                'callback' => function () {
                    $this->sayWrongAnswer();
                    $this->askConfirmSymptoms();
                }
            ]
        ]);
    }

    public function checkConfirmedSymptomsRecommendation()
    {
        $hasDifficultyBreathing = $this->symptoms->contains(SymptomsType::DIFICULDADE_RESPIRAR);
        if ($hasDifficultyBreathing) {
            return $this->sayToLookForEmergyCareAndYourLeader();
        }

        $hasFever = $this->symptoms->contains(SymptomsType::FEBRE);
        if ($hasFever) {
            return $this->checkFeverRecommendation();
        }

        $mildSymptoms = [
            SymptomsType::TOSSE_SECA,
            SymptomsType::CANSACO,
            SymptomsType::DOR_CORPO,
            SymptomsType::DOR_GARGANTA,
            SymptomsType::CONGESTAO_NASAL,
            SymptomsType::CORIZA,
            SymptomsType::DIARREIA,
            SymptomsType::SEM_PALADAR,
        ];

        $countConfirmedMildSymptoms = $this->symptoms->intersect($mildSymptoms);
        if ($countConfirmedMildSymptoms) {
            return $this->sayToLookForEmergyCareAndYourLeader();
        }
    }

    public function checkFeverRecommendation()
    {
        $question = 'Sobre a sua febre, responda:
*1* Faz pouco tempo que começou?
*2* A sua febre é persistente? (está tomando remédio e ela volta).

Responda com o número 1 ou 2 referente a sua febre.';

        $this->ask($question, [
            [
                'pattern' => '1',
                'callback' => function () {
                    $this->levelFever = '1';
                    $this->sayToLookForEmergyCareAndYourLeader();
                }
            ],
            [
                'pattern' => '2',
                'callback' => function () {
                    $this->levelFever = '2';
                    $this->sayToLookForEmergyCareAndYourLeader();
                }
            ],
            [
                'pattern' => '',
                'callback' => function () {
                    $this->sayWrongAnswer();
                    $this->checkFeverRecommendation();
                }
            ]
        ]);
    }

    public function checkMildSymptomRecommendation($countSymptoms)
    {
        return $this->sayToLookForEmergyCareAndYourLeader();
    }

    public function sayStayHomeOrHealthUnit()
    {
        $this->say('Baseado em suas respostas e nos sinais de sintomas da COVID-19, a recomendação é de ficar em casa.
Em caso de surgimento de novos sintomas ou de agravamento dos sintomas atuais, procure uma unidade de saúde próximo da sua casa.');
    }

    public function sayToStayHomeOrLookForEmergyCare()
    {
        $this->say('Baseado em suas respostas e nos sinais de sintomas da COVID-19, a recomendação é de, por enquanto, ficar em casa.
Em caso de surgimento de novos sintomas ou de agravamento dos sintomas atuais, procure uma unidade de pronto atendimento próximo da sua casa.');
    }

    public function sayToLookForEmergyCare()
    {
        $this->say('Baseado em suas respostas e nos sinais de sintomas da COVID-19, a orientação é que você procure uma unidade de pronto atendimento para avaliação.');
    }

    public function  sayToLookForEmergyCareAndYourLeader()
    {
        $this->say('Monitoramento realizado. Em caso de agravamento ou de surgimento de novos sintomas, procure assistência médica e entre em contato com seu superior imediato. Até amanhã!');
    }

    public function sayAllGood()
    {
        $this->say('Que bom! Aparentemente, você está bem. Até amanhã!');
    }

    public function askSymptomatics()
    {
        $question = 'Boa noite, você respondeu que estava com alguns sintomas. Você continua se sentindo mal? Responda com *Sim* ou *Não*.';

        $this->ask($question, [
            [
                'pattern' => 'sim',
                'callback' => function () {
                    $this->confirmStillHaveSymptoms = 'sim';
                    $this->askConfirmBadFeelings();
                }
            ],
            [
                'pattern' => 'nao|não',
                'callback' => function () {
                    $this->confirmStillHaveSymptoms = 'nao';
                    $this->sayAllGood();
                }
            ],
            [
                'pattern' => '',
                'callback' => function () {
                    $this->sayWrongAnswer();
                    $this->askSymptomatics();
                }
            ]
        ]);
    }

    public function askComplementRiskGroup()
    {
        $this->askHeartOrLungProblems();
    }

    public function askHeartOrLungProblems()
    {
        $question = 'Você possui algum problema cardíaco (hipertensão) ou pulmonares? Responda com *Sim* ou *Não*.';

        $this->ask($question, [
            [
                'pattern' => 'sim',
                'callback' => function () {
                    $this->haveHearOrLungProblem = 'sim';
                    $this->askDiabetes();
                }
            ],
            [
                'pattern' => 'nao|não',
                'callback' => function () {
                    $this->haveHearOrLungProblem = 'nao';
                    $this->askDiabetes();
                }
            ],
            [
                'pattern' => '',
                'callback' => function () {
                    $this->sayWrongAnswer();
                    $this->askHeartOrLungProblems();
                }
            ]
        ]);
    }

    public function askDiabetes()
    {
        $question = 'Você possui diabetes? Responda com *Sim* ou *Não*.';

        $this->ask($question, [
            [
                'pattern' => 'sim',
                'callback' => function () {
                    $this->haveDiabetes = 'sim';
                    $this->askPregnant();
                }
            ],
            [
                'pattern' => 'nao|não',
                'callback' => function () {
                    $this->haveDiabetes = 'nao';
                    $this->askPregnant();
                }
            ],
            [
                'pattern' => '',
                'callback' => function () {
                    $this->sayWrongAnswer();
                    $this->askDiabetes();
                }
            ]
        ]);
    }

    public function askPregnant()
    {
        $question = 'Você é gestante? Responda com *Sim* ou *Não*.';

        $this->ask($question, [
            [
                'pattern' => 'sim',
                'callback' => function () {
                    $this->isPregnant = 'sim';
                    $this->sayThanks();
                }
            ],
            [
                'pattern' => 'nao|não',
                'callback' => function () {
                    $this->isPregnant = 'nao';
                    $this->sayThanks();
                }
            ],
            [
                'pattern' => '',
                'callback' => function () {
                    $this->sayWrongAnswer();
                    $this->askPregnant();
                }
            ]
        ]);
    }

    public function sayThanks()
    {
        $this->say('Obrigado por responder!');
    }
}
