# Docker CoronaDados
Inicialização do projeto utilizando Docker.

## Pré-requisitos
###  Clone
Clone este repositório na sua máquina local usando `https://github.com/CoronaDados/base`

### Docker
* [Linux](https://docs.docker.com/engine/install/)
* [Windows](https://docs.docker.com/docker-for-windows/install/)
* [OS X](https://docs.docker.com/docker-for-mac/install/)

### Docker Compose
Acesse o [link](https://docs.docker.com/compose/install/) e selecione seu SO.

## Como começar a funcionar
Fácil! Navegue para o diretório em que você clonou o projeto. Execute os seguintes comandos neste diretório:

``` 
docker-compose up -d
```

O comando `docker-compose` extrai as imagens do Docker Hub e as víncula com base nas informações contidas no arquivo docker-compose.yml. 
Isso criará portas, links entre contêineres e configurará aplicativos conforme necessário. 
Após a conclusão do comando, agora podemos visualizar o status dos nossos containers.

Deverá ter quatro containers:
- coronadados-mysql
- coronadados-app
- coronadados-webserver

**Nota:**
- Na primeira vez em que você executar isso, levará alguns minutos para iniciar, pois será necessário fazer o download das imagens para todos os três serviços.

### Instalar dependências
Precisamos executar a instalação do composer para extrair todas as bibliotecas que compõem o Laravel executando o seguinte comando.

```
docker run --rm --interactive --tty --volume "$PWD":/app composer --ignore-platform-reqs
```

### Configurando o .env
Crie o **.env** com base no **.env.example** conforme o exemplo abaixo:

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=coronadados
DB_USERNAME=corona_user
DB_PASSWORD=corona_pass
```

### Migrations
Agora que o .env já está configurado, vamos executar as migrations para criar as tabelas no banco de dados `coronadados`

```
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate:install
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed --class=PermissionTableSeeder
docker-compose exec app php artisan db:seed --class=UsersTableSeeder
```

### Adicionar permissão no storage
```
docker-compose run --rm --no-deps app php artisan cache:clear
docker-compose run --rm --no-deps app chmod -R 777 storage/
docker-compose run --rm --no-deps app composer dump-autoload
```

### Acessando o projeto
Depois de executar os comandos mencionados anteriormente, o aplicativo estará pronto para uso em http://localhost:8000.

## Resumo de variáveis de ambiente
- `MYSQL_DATABASE` - nome do banco de dados (coronadados)
- `MYSQL_USER`, `MYSQL_PASSWORD` - Essas variáveis são opcionais, usadas em conjunto para criar um novo usuário e definir a senha do usuário. 
- `MYSQL_ROOT_PASSWORD` - Essa variável é obrigatória e específica a senha que será configurada para a conta do superusuário raiz.

## Links Úteis
* [Landing page](https://coronadados.com.br/)
* [Ambiente de teste](http://teste-fiesc.coronadados.com.br/login)
* [Sistema](http://empresas.coronadados.com.br/)
   
## Construído com
* MySQL 5.7
* PHP 7.x
* Laravel 7.0
