# Dictionary API

API RESTful para um dicionário de palavras em inglês, com funcionalidades de autenticação, busca de palavras, histórico de consultas e favoritos.

## Tecnologias Utilizadas

- PHP 8.3
- Laravel 12
- Laravel Passport (Autenticação)
- Redis (Cache)
- MySQL (Banco de Dados)

## Requisitos

- PHP 8.3 ou superior
- Composer
- Redis
- MySQL 8.0 ou superior

## Instalação

1. Clone o repositório:
```bash
git clone [URL_DO_REPOSITÓRIO]
cd dictionary-api
```

2. Instale as dependências do PHP:
```bash
composer install
```

3. Configure o ambiente:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure o banco de dados MySQL:
- Crie um banco de dados MySQL
- Configure as credenciais no arquivo `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dictionary
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. Execute as migrações:
```bash
php artisan migrate
```

6. Importe o dicionário de palavras:
```bash
php artisan dictionary:import
```

7. Inicie o servidor:
```bash
php artisan serve
```

## Uso

A API estará disponível em `http://localhost:8000`. Para autenticação, você precisará:

1. Criar uma conta usando o endpoint `/auth/signup`
2. Fazer login usando o endpoint `/auth/signin`
3. Usar o token retornado no header `Authorization` para as requisições subsequentes

## Endpoints Principais

- `GET /` - Mensagem de boas-vindas
- `POST /auth/signup` - Criar conta
- `POST /auth/signin` - Fazer login
- `GET /entries/en` - Listar palavras
- `GET /entries/en/{word}` - Buscar palavra específica
- `POST /entries/en/{word}/favorite` - Adicionar aos favoritos
- `DELETE /entries/en/{word}/unfavorite` - Remover dos favoritos
- `GET /user/me` - Perfil do usuário
- `GET /user/me/history` - Histórico de consultas
- `GET /user/me/favorites` - Lista de favoritos

## Cache

A API utiliza Redis para cache, melhorando a performance das requisições repetidas. Os headers `x-cache` e `x-response-time` indicam o status do cache e o tempo de resposta.

---

This is a challenge by [Coodesh](https://coodesh.com/)
