@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')   
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="accordion" id="accordionHelp1">
            <div class="card">
                <div class="card-header collapsed" id="headingHelp1" data-toggle="collapse" data-target="#collapseHelp1" aria-expanded="false" aria-controls="collapseHelp1">
                    <h5 class="mb-0">Qual o objetivo do CoronaDados?</h5>
                </div>
                <div id="collapseHelp1" class="collapse" aria-labelledby="headingHelp1" data-parent="#accordionHelp1">
                    <div class="card-body">
                        <p>Construir o maior banco de dados contra o coronavírus, e através da coleta de dados individuais de sintomas, conseguir referenciar suspeitas e casos positivos do coronavírus a nível de CEP e bairro. Para que ações possam ser tomadas pelo poder público, empresas e comunidades, tendo ciência que pessoas daquela localidade estão em potencial risco.</p>
                    </div>
                </div>
            </div>          
        </div>
        <br>
        <div class="accordion" id="accordionHelp2">
            <div class="card">
                <div class="card-header collapsed" id="headingHelp2" data-toggle="collapse" data-target="#collapseHelp2" aria-expanded="false" aria-controls="collapseHelp2">
                    <h5 class="mb-0">Qual a função do Admin, Líder e Médico?</h5>
                </div>
                <div id="collapseHelp2" class="collapse" aria-labelledby="headingHelp2" data-parent="#accordionHelp2">
                    <div class="card-body">
                        <ul>
                            <li><strong>Admin</strong>: Ao cadastrar um empresa, a pessoa que realizou o cadastro automaticamente vira um admin. Os admins terão todo acesso a empresa vinculada como cadastrar e editar informação de colaboradores, realizar o monitoramento, cadastrar mais funções, visão do dashboard, etc.</li>
                            <li><strong>Líder</strong>: O líder na empresa é como um supervisor para monitorar os colaboradores a qual ele é o líder. Com isso a responsabilidade do líder é de diariamente verificar se seus colaboradores responderam os questionamentos, ou buscar a informações desses questionamentos junto ao colaborador.</li>
                            <li><strong>Médico</strong>: A função do médico não necessariamente precisa ser um médico, pode ser um agente da saúde, ou o responsável pelo monitoramento dos colaboradores com suspeita ou com os casos confirmados com o COVID-19. Para isso o papel do médico é conduzir as ações necessárias para esses casos informando o poder público ou responsáveis, e documentar no sistema sobre informações atualizadas destes casos.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="accordion" id="accordionHelp3">
            <div class="card">
                <div class="card-header collapsed" id="headingHelp3" data-toggle="collapse" data-target="#collapseHelp3" aria-expanded="false" aria-controls="collapseHelp3">
                    <h5 class="mb-0">Posso criar mais funções?</h5>
                </div>
                <div id="collapseHelp3" class="collapse" aria-labelledby="headingHelp3" data-parent="#accordionHelp3">
                    <div class="card-body">
                        <p>Sim. A criação de função está disponível apenas para usuários Admin da empresa. Você poderá criar funções específicas para sua empresa determinando as permissões que cada função terá dentro do sistema.</p>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="accordion" id="accordionHelp4">
            <div class="card">
                <div class="card-header collapsed" id="headingHelp4" data-toggle="collapse" data-target="#collapseHelp4" aria-expanded="false" aria-controls="collapseHelp4">
                    <h5 class="mb-0">O mapa está disponível para população?</h5>
                </div>
                <div id="collapseHelp4" class="collapse" aria-labelledby="headingHelp4" data-parent="#accordionHelp4">
                    <div class="card-body">
                        <p>Sim. O mapa é atualizado com os casos suspeitos e confirmados. Fica disponível através do endereço <a href="http://governo.coronadados.com.br/" target="_blank">http://governo.coronadados.com.br/</a> e pode ser visualizado em qualquer dispositivo com internet.</p>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="accordion" id="accordionHelp5">
            <div class="card">
                <div class="card-header collapsed" id="headingHelp5" data-toggle="collapse" data-target="#collapseHelp5" aria-expanded="false" aria-controls="collapseHelp5">
                    <h5 class="mb-0">Outras empresas de SC podem utilizar o sistema?</h5>
                </div>
                <div id="collapseHelp5" class="collapse" aria-labelledby="headingHelp5" data-parent="#accordionHelp5">
                    <div class="card-body">
                        <p>Sim.</p>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="accordion" id="accordionHelp6">
            <div class="card">
                <div class="card-header collapsed" id="headingHelp6" data-toggle="collapse" data-target="#collapseHelp6" aria-expanded="false" aria-controls="collapseHelp6">
                    <h5 class="mb-0">Outros estados podem utilizar o sistema?</h5>
                </div>
                <div id="collapseHelp6" class="collapse" aria-labelledby="headingHelp6" data-parent="#accordionHelp6">
                    <div class="card-body">
                        <p>O sistema é open source (código aberto), ou seja, está disponível para qualquer estado aplicar conforme sua necessidade. Para acessar o código fonte do sistema acesse o endereço <a href="https://github.com/CoronaDados" target="_blank">https://github.com/CoronaDados</a></p>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="accordion" id="accordionHelp7">
            <div class="card">
                <div class="card-header collapsed" id="headingHelp7" data-toggle="collapse" data-target="#collapseHelp7" aria-expanded="false" aria-controls="collapseHelp7">
                    <h5 class="mb-0">Como funciona a importação da planilha de colaboradores?</h5>
                </div>
                <div id="collapseHelp7" class="collapse" aria-labelledby="headingHelp7" data-parent="#accordionHelp7">
                    <div class="card-body">
                        <p>Existem duas opções para realizar a importação de colaboradores:</p>
                        <ul>
                            <li><strong>Importação de líderes</strong>: Através de um arquivo CSV ou XLS você pode importar todos os colaboradores que irão liderar outros colaboradores. Automaticamente essas pessoas serão colaboradores e líderes.
                                Durante a importação, o sistema sempre irá verificar se o líder definido para a pessoa já existe na base de dados, caso não exista o líder dessa pessoa será vinculado ao usuário que fez a importação da planilha.</li>
                            <li><strong>Importação de colaboradores</strong>: Assim como a importação de líder, a de colaborador é realizada através de um arquivo CSV ou XLS. O processo é o mesmo que o líder, só que neste caso o sistema irá salvar a pessoa apenas como colaborador e não como líder.</li>
                        </ul>
                        <p><small>Observação: O sistema também permite cadastrar um colaborador através do cadastro de colaborador, ou atribuir a função a um colaborador já cadastrado.</small></p>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="accordion" id="accordionHelp8">
            <div class="card">
                <div class="card-header collapsed" id="headingHelp8" data-toggle="collapse" data-target="#collapseHelp8" aria-expanded="false" aria-controls="collapseHelp8">
                    <h5 class="mb-0">Quais colunas existem na planilha? É preciso preencher todas?</h5>
                </div>
                <div id="collapseHelp8" class="collapse" aria-labelledby="headingHelp8" data-parent="#accordionHelp8">
                    <div class="card-body">
                        <p>Nem todas as colunas são obrigatórias, mas quanto mais informações possuir, melhor será o monitoramentos e ações para todas as situações. Veja abaixo as colunas que existem e quais são obrigatórias.</p>
                        <ul>
                            <li><strong>name</strong>: Nome completo do colaborador/pessoa. <code>(Obrigatório)</code></li>
                            <li><strong>email</strong>: E-mail do colaborador/pessoa. <code>(Obrigatório)</code></li>
                            <li><strong>phone</strong>: Telefone celular do colaborador/pessoa, este campo é fundamental para o monitoramento da pessoa, pois é através deste número que será enviado o questionamento diário. <code>(Obrigatório)</code></li>
                            <li><strong>cpf</strong>: CPF do colaborador/pessoa. <code>(Obrigatório)</code></li>
                            <li><strong>sector</strong>: Setor que o colaborador/pessoa trabalha. Atualmente esta coluna aceita apenas os valores Administrativo, Financeiro, Operacional, Comercial, Médico ou Diretoria.</li>
                            <li><strong>birthday</strong>: Data de nascimento do colaborador/pessoa, não é obrigatório, mas é importante para identificar se a pessoa é considerado como idoso e entra no grupo de risco.</li>
                            <li><strong>gender</strong>: Sexo colaborador/pessoa.</li>
                            <li><strong>risk_group</strong>: Campo para identificar se a pessoa está dentro do grupo de risco.</li>
                            <li><strong>status</strong>: Por padrão coloque 1.</li>
                            <li><strong>cep</strong>: CEP do endereço onde o colaborador/pessoa reside. Importante para mostrar no mapa caso o colaborador/pessoa esteja com suspeita ou confirmação do COVID-19.</li>
                            <li><strong>ibge</strong>: Em breve.</li>
                            <li><strong>state</strong>: Estado de onde o colaborador/pessoa reside.</li>
                            <li><strong>city</strong>: Cidade de onde o colaborador/pessoa reside.</li>
                            <li><strong>neighbordhood</strong>: Bairro de onde o colaborador/pessoa reside.</li>
                            <li><strong>street</strong>: Logradouro de onde o colaborador/pessoa reside.</li>
                            <li><strong>complement</strong>: Complemento de onde o colaborador/pessoa reside.</li>
                            <li><strong>number</strong>: Número de onde o colaborador/pessoa reside.</li>
                            <li><strong>cpf_lider</strong>: CPF do líder que irá liderar/supervisionar este colaborador/pessoa.</li>                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="accordion" id="accordionHelp9">
            <div class="card">
                <div class="card-header collapsed" id="headingHelp9" data-toggle="collapse" data-target="#collapseHelp9" aria-expanded="false" aria-controls="collapseHelp9">
                    <h5 class="mb-0">O que acontece se na importação possuir um usuário que já existe no sistema?</h5>
                </div>
                <div id="collapseHelp9" class="collapse" aria-labelledby="headingHelp9" data-parent="#accordionHelp9">
                    <div class="card-body">
                        <p>O sistema irá verificar se o CPF já existe na base de dados, e irá atualizar as informações conforme estão na planilha de importação. Os campos que estiverem vazios na planilha serão desconsiderados para alteração.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>   
@endsection