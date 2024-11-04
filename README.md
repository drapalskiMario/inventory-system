# Aplicação de Inventário Simples

Este projeto é uma aplicação de inventário simples que permite criar produtos e clientes, além de criar pedidos vinculados a eles. O sistema é dividido em duas partes: um backend desenvolvido em CakePHP e um frontend em Angular.

## Tecnologias Utilizadas

- **Backend**: CakePHP
- **Frontend**: Angular
- **Containerização**: Docker
- **Runtime**: Node.js

## Pré-requisitos

Antes de executar o projeto, certifique-se de ter os seguintes itens instalados:

- [Docker](https://www.docker.com/get-started)
- [Node.js](https://nodejs.org/en/)

## Execução do Projeto

Para configurar e executar a aplicação, utilize o seguinte comando:

```bash
make setup
```

Este comando irá iniciar todos os serviços necessários.

## URLs de Acesso

- Frontend: `http://localhost:4200`
- Backend: `http://localhost:3000`

## Teste das Rotas da API

Para facilitar o teste das rotas da API, um arquivo de coleção do Postman chamado `inventory_system.postman_collection.json` foi incluído no projeto. Você pode importar este arquivo no Postman para testar rapidamente as rotas disponíveis.

### Como usar o Postman

1. Abra o Postman.
2. Clique em "Import" no canto superior esquerdo.
3. Selecione o arquivo `inventory_system.postman_collection.json` e importe-o.
4. Após a importação, você verá todas as rotas da API configuradas.
5. Execute as requisições conforme necessário.

Certifique-se de que o servidor backend está em execução em `http://localhost:3000` para que as requisições funcionem corretamente.

## Configuração de Servidor de Email

Para que a aplicação envie e-mails corretamente, você precisa configurar os parâmetros de servidor de e-mail no arquivo `server/config/app_local.php`. É recomendado utilizar o [Mailtrap](https://mailtrap.io/) para testes de envio de e-mail. Você pode obter suas credenciais do Mailtrap e configurá-las da seguinte maneira:

```php
'EmailTransport' => [
        'default' => [
            'host' => 'sandbox.smtp.mailtrap.io',
            'port' => 587,
            'username' => 'username',
            'password' => 'password',
            'className' => 'Smtp',
            'tls' => true,
        ],
        'queue' => [
            'className' => 'Queue.Queue',
            'transport' => 'default',
        ],
    ],
    'Email' => [
        'adminEmail' => 'admin@example.com',
        'default' => [
            'transport' => 'default',
            'from' => 'from@example.com',
            'charset' => 'utf-8',
            'headerCharset' => 'utf-8',
        ],
    ] ,
```

## Observações

Este projeto é destinado apenas para fins de laboratório e desenvolvimento local. Para usá-lo em um ambiente de produção, será necessário implementar várias melhorias e configurações, incluindo, mas não se limitando a:

- **Segurança**: Implementar medidas de segurança adequadas, como autenticação e autorização.
- **Gerenciamento de erros**: Adicionar um tratamento de erros robusto.
- **Configurações de ambiente**: Garantir que as variáveis de ambiente e configurações sejam adequadas para o ambiente de produção.
- **Desempenho**: Avaliar e otimizar o desempenho da aplicação.
- **Backup e recuperação**: Implementar estratégias de backup e recuperação de dados.

Por favor, considere estas observações antes de implantar este projeto em produção.
