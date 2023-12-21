# API TOKENIZATION PHP

## Descrição

Este é um projeto em PHP desenvolvido para fins de estudo. Ele oferece um serviço de autenticação usando JWT. Essa API oferece a possibilidade de autenticar tanto com email e senha como tambem com login social. 

## Requisitos do Sistema

Antes de iniciar a instalação e execução do projeto, certifique-se de que seu ambiente atende aos seguintes requisitos:

- PHP >= 7.4

## Instalação

Siga os passos abaixo para configurar e executar o projeto localmente:

1. Clone o repositório para o seu ambiente de desenvolvimento:

    ```bash
    git clone https://github.com/jonathancmatos/api-tokenization-php.git
    ```

2. Navegue até o diretório do projeto:

    ```bash
    cd api-tokenization-php
    ```

3. Instale as dependências usando [compositor ou outra ferramenta de gerenciamento de dependências]:

    ```bash
    composer install
    ```

4. Copie o arquivo de configuração e ajuste conforme necessário:

    ```bash
    cp config.example.php config.php
    ```

5. Execute o servidor embutido do PHP:

    ```bash
    php -S localhost:8000
    ```

6. Abra o navegador e acesse [http://localhost:8000](http://localhost:8000).

## Configuração

Para personalizar o projeto, edite o arquivo `config.php` com as configurações específicas do seu ambiente. Para esse projeto foi usado o ```phpdotenv``` para guardar e acessar variaveis de ambiente no arquivo .env. 

## Banco de Dados

O projeto requer um banco de dados. Siga os passos abaixo para cria-lo:

1. **Crie o Banco de Dados:**
   - Execute o seguinte comando SQL no seu sistema de gerenciamento de banco de dados (substitua `nome_do_banco` pelo nome desejado):

     ```sql
     CREATE DATABASE nome_do_banco;
     ```

2. **Crie a tabela users e seus atributos:**
   ```sql 
    create table users (
        id         int auto_increment primary key,
        name       varchar(255)                          not null,
        email      varchar(255)                          not null,
        passwd     varchar(255)                          null,
        phone      varchar(255)                          null,
        token      mediumtext                            null,
        id_google  varchar(255)                          null,
        updated_at timestamp default current_timestamp() not null,
        created_at timestamp default current_timestamp() not null
    );
    ```

## Documentação da API

A API deste projeto oferece os principais endpoints:

### [Nova Conta]

- **Método:** POST
- **Descrição:** Criar um nova conta usando email e senha
- **Exemplo de Uso:**
  ```http
  /api/signup

### [Login com Email e Senha]

- **Método:** POST
- **Descrição:** Logar com a conta usando email e senha
- **Exemplo de Uso:**
  ```http
  /api/signin

### [Login Social - Google]

- **Método:** POST
- **Descrição:** Logar com a conta usando email e senha
- **Exemplo de Uso:**
  ```http
  /api/google-sign-in

### [Usuario Logado]

- **Método:** GET
- **Descrição:** Retorna os dados do usuário logado usando o access_token como referencia. 
- **Exemplo de Uso:**
  ```http
  /api/current-user

### [Sair da Conta]

- **Método:** POST
- **Descrição:** Realiza logout da conta. 
- **Exemplo de Uso:**
  ```http
  /api/logout

  
### [Refresh Token]

- **Método:** POST
- **Descrição:** Atualiza o tempo de vida do access_token 
- **Exemplo de Uso:**
  ```http
  /api/refresh-token

## Licença

Este projeto está licenciado sob a licença [especificar a licença, por exemplo, MIT] - veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## Contato

Para mais informações, entre em contato com via [contato@devjonathancosta.com].

