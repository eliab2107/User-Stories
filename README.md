# 📌 User Stories

Breve descrição do projeto, explicando seu propósito e contexto.

## 📝 Índice

- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Configuração do Ambiente](#configuração-do-ambiente)
- [Execução do Projeto](#execução-do-projeto)
- [Testando a API com Postman](#testando-a-api-com-postman)
- [Justificativas de Escolhas](#justificativas-de-escolhas)
- [Melhorias Futuras](#melhorias-futuras)
- [Contribuindo](#contribuindo)
- [Contato](#contato)

---

## 📖 Sobre o Projeto

Este projeto é uma API integrada a um banco de dados MySQL que permite armazenar e regatar informações sobre transferências monetárias, foi pensada para servir a uma aplicação em que um usuário possa categorizar gastos e recebimentos.

## 🚀 Tecnologias Utilizadas

Este projeto foi desenvolvido com as seguintes tecnologias:

- [Laravel](https://laravel.com/) - Framework PHP
- [MySQL](https://www.mysql.com/) - Banco de dados relacional
- [Postman](https://www.postman.com/) - Testes de API
- Para suporte ao ambiente de desenvolvimento local utilizei o laragon.

## ⚙️ Configuração do Ambiente

Siga os passos abaixo para configurar e rodar o projeto localmente:

1. **Clone o repositório:**

   ```sh
   git clone https://github.com/seu-usuario/nome-do-repositorio.git
   cd nome-do-repositorio
   ```

2. **Instale as dependências:**

   ```sh
   composer install
   ```

3. **Configure o arquivo **``**:**

   - Copie o arquivo de exemplo:
     ```sh
     cp .env.example .env
     ```
   - Configure as credenciais do banco de dados e outras configurações necessárias.

4. **Gere a chave do aplicativo:**

   ```sh
   php artisan key:generate
   ```

5. **Execute as migrations:**

   ```sh
   php artisan migrate --seed
   ```

6. **Inicie o servidor:**

   ```sh
   php artisan serve
   ```

O projeto estará disponível em `http://localhost:8000`.

## 🛠️ Execução do Projeto

Para rodar o projeto, siga os passos acima e utilize ferramentas como Postman ou Insomnia para testar os endpoints.

## 🧪 Testando a API com Postman

1. **Importe a Collection:**

   - Abra o Postman e importe o arquivo `user stories.postman_collection.json`.  

2. **Endpoints Principais:**

   - **Criar uma categoria:** `POST /api/categorias`
   - **Listar categorias:** `GET /api/categorias`
   - **Criar uma transação:** `POST /api/transacoes`
   - **Listar transações:** `GET /api/transacoes`

3. **Autenticação:**

   - O projeto utiliza autenticação via **Bearer Token**.
   - Gere um token e inclua no cabeçalho das requisições:
     ```json
     {
       "Authorization": "Bearer seu_token"
     }
     ```
  
   - Um token é gerado sempre que um usuário realiza um login bem sucedido. A estrutura foi pensada para que o frontend pudesse receber este token e utiliza-lo para autenticar as requisições de uma sessão. 
    *Instruções*: 
        1.1 Para que os testes funcionem é preciso que sejam executados primeiramente uma chamada para /register usando o email e senha desse usuário deve ser feito o login.
        1.2 Ao efetuar o login você receberá um token, é com este token que você conseguirá validar as suas próximas requisições.
        1.3 Adicione o token ao Authorization:Bearer Token. 
        1.4 ATENÇÃO: Sem este token todas as demais requisições serão recusadas. 
        1.5 Um usuário gera um token novo sempre que faz login. 
        1.6 Para simplificar os testes, os tokens não são apagados então você poderá reutiliza-los, mas será sempre pertencente ao mesmo usuário.

## 🎯 Justificativas de Escolhas

 - Relação entre categoria e transações: Achei que poderia ser mais interessante para um usuário poder deletar uma categoria sem apagar transações. Para isso eu criei uma rota que permite alterar a categoria de uma função e uma rota para alterar o nome de uma categoria, dessa forma há máxima liberdade.
 - MySQL: Escolhi esse banco simplesmente por estar mais acessível para a configuração no ambiente local.
 - FrontEnd: Deixei no projeto apenas um esboço do que estava construindo. Abandonei-o por perceber que investiria muito tempo para ter um bom resultado e que seria melhor investir em funcionalidades e documentação.


## 📌 Melhorias Futuras

 - Adicionar indices ára otimizar consultas feitas nas tabelas de transações e categorias. Estas serão as maiores tabelas, percorre-las por completo é bem mais custuso que o necessário.
 - Adicionar funcionalidade de cache.
 - Adicionar testes automatizados.
 - Criar um frontend.
 - Armazenar no front apenas uma fração dos dados e buscar mais informações a medida que for necessário.

