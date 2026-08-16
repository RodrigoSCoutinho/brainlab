<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="220" alt="Laravel Logo">
</p>

<h1 align="center">🧠 BrainLab</h1>

<p align="center">
  <b>Plataforma de preparação para o exame de admissão do IFRN</b><br>
  Questões por assunto, simulados cronometrados, redações corrigidas por IA/professor e videoaulas — tudo em um só lugar.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-%5E8.0.2-777bb4" alt="PHP">
  <img src="https://img.shields.io/badge/Laravel-9.19-ff2d20" alt="Laravel">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479a1" alt="MySQL">
  <img src="https://img.shields.io/badge/license-MIT-blue" alt="License">
</p>

---

## Sobre o projeto

BrainLab é um Trabalho de Conclusão de Curso (TCC): uma aplicação web que ajuda estudantes do 9º ano a se preparar para o **processo seletivo do IFRN**. O sistema é construído em **Laravel 9** (Blade + CSS puro, sem framework de frontend), usa **MySQL** como banco de dados e roda em **Docker**.

Três papéis de usuário compartilham a mesma base de código:

| Papel | O que pode fazer |
|---|---|
| `student` (padrão) | Praticar questões, fazer simulados, enviar redações, ver videoaulas |
| `professor` | Tudo do aluno + corrigir redações, ver progresso dos alunos, gerenciar banco de questões e avisos |
| `admin` | Tudo do professor + gerenciar usuários e papéis (`/admin`) |

O papel é armazenado em `users.role` e verificado via `User::isStudent()`, `isProfessor()`, `isAdmin()` e `isStaff()` (`app/Models/User.php`), além do middleware de rota `role:professor,admin`.

---

## Funcionalidades

- **Landing page** (`/`) — página de marketing com planos, redireciona para `/dashboard` se já autenticado
- **Autenticação** — registro e login com `Auth::attempt()`, sessão regenerada no login/logout
- **Prática** (`/practice`) — uma questão de múltipla escolha por vez, com feedback imediato
- **Simulado** (`/exam`) — bateria de questões aleatórias com cronômetro, correção automática e revisão de gabarito
- **Redações** (`/essays`) — aluno envia redação, pode pedir análise por IA (simulada ou via OpenAI, ver `OPENAI_API_KEY`) e recebe nota por competência (padrão ENEM, 5 competências de 0–200)
- **Painel do professor** (`/professor/*`) — corrigir redações com comentários por linha, acompanhar progresso de alunos, gerenciar banco de questões (CRUD) e publicar avisos
- **Painel do admin** (`/admin`) — listar usuários e alterar papéis
- **Configurações** (`/settings`) — editar perfil, trocar senha, configurar chave OpenAI pessoal
- **Vídeos** (`/videos`) — videoaulas do YouTube organizadas por assunto (Matemática, Língua Portuguesa, Ética e Cidadania)
- **Checkout fictício** (`/checkout/{plan}`) — página de pagamento simulada (PIX, cartão, boleto), sem processamento real

Detalhes completos de escopo, fluxos e schema do banco estão em [`Requirements.md`](./Requirements.md).

---

## Stack técnica

| Camada | Tecnologia |
|---|---|
| Backend | PHP ^8.0.2, Laravel ^9.19 |
| Autenticação | Laravel Sanctum ^3.0 |
| Banco de dados | MySQL 8.0 (via Docker) |
| Frontend | Blade + CSS puro (variáveis CSS), Vite ^4.0, Axios, Lodash |
| Ícones | FontAwesome 6.5.1 (CDN) — nenhuma imagem usada |
| Testes | PHPUnit ^9.5 |
| Lint | Laravel Pint |
| Infra local | Docker Compose (MySQL + phpMyAdmin) |

---

## Pré-requisitos

Instale antes de começar:

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [PHP](https://www.php.net/downloads) >= 8.0.2
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/) >= 16

---

## Passo a passo — setup automático (recomendado)

O repositório traz um script único, `setup.sh`, que faz **tudo** em Linux, macOS e Windows (via Git Bash).

```bash
git clone <url-do-repositorio>
cd brainlab
chmod +x setup.sh
./setup.sh
```

> **Windows:** abra o **Git Bash** (instalado junto com o Git for Windows), navegue até a pasta do projeto e rode `./setup.sh`. PowerShell/CMD não executam o script.

O script executa, nesta ordem:

| # | Etapa |
|---|---|
| 1 | Detecta o sistema operacional |
| 2 | Verifica/instala Docker, PHP, Composer e Node.js |
| 3 | `composer install` — dependências PHP |
| 4 | `npm install` — dependências JS |
| 5 | Copia `.env.example` → `.env` e ajusta as variáveis de banco para o MySQL do Docker |
| 6 | `docker compose up -d` — sobe MySQL 8.0 e phpMyAdmin |
| 7 | Aguarda o MySQL ficar saudável |
| 8 | `php artisan key:generate` |
| 9 | `php artisan migrate` |
| 10 | `php artisan db:seed` — popula banco de questões e contas de teste |
| 11 | Limpa caches de config/rota/view |
| 12 | Sobe `php artisan serve` (porta 8000) e `npm run dev` (Vite, porta 5173) |

Ao final, o próprio terminal exibe as URLs e credenciais de teste.

---

## Passo a passo — setup manual

Se preferir rodar cada etapa manualmente (ou estiver em um ambiente onde o script não se aplica):

**1. Clonar e instalar dependências**

```bash
git clone <url-do-repositorio>
cd brainlab
composer install
npm install
```

**2. Configurar o ambiente**

```bash
cp .env.example .env
php artisan key:generate
```

Edite o `.env` gerado e ajuste o bloco de banco de dados para bater com o `docker-compose.yml`:

```env
APP_NAME=BrainLab

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=brainlab
DB_USERNAME=brainlab
DB_PASSWORD=brainlab
```

Se quiser usar análise de redação por IA de verdade, defina também:

```env
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4o-mini
```

**3. Subir o banco de dados**

```bash
docker compose up -d
```

Isso inicia dois containers:

| Serviço | Porta | Credenciais |
|---|---|---|
| MySQL 8.0 | 3306 | `brainlab` / `brainlab` (root: `root`/`root`) |
| phpMyAdmin | 8080 | usuário `root`, senha `root` |

**4. Migrar e popular o banco**

```bash
php artisan migrate
php artisan db:seed
```

O seeder cria as contas de teste e três bancos de questões (Matemática, Língua Portuguesa, Ética e Cidadania).

**5. Subir a aplicação**

Em dois terminais separados:

```bash
php artisan serve      # backend em http://localhost:8000
npm run dev             # Vite em http://localhost:5173 (hot reload de CSS/JS)
```

---

## Acessando o sistema

| Serviço | URL |
|---|---|
| Aplicação | http://localhost:8000 |
| Vite (dev assets) | http://localhost:5173 |
| phpMyAdmin | http://localhost:8080 |
| MySQL | localhost:3306 |

### Contas de teste (criadas pelo seeder)

| Papel | E-mail | Senha |
|---|---|---|
| Admin | `admin@brainlab.com` | `password` |
| Professor | `prof@brainlab.com` | `password` |

> Troque essas senhas (ou remova as contas) antes de qualquer deploy público. Contas de aluno são criadas normalmente pela tela de registro (`/register`).

---

## Comandos úteis do dia a dia

```bash
# Parar os containers (mantém os dados)
docker compose stop

# Reiniciar os containers
docker compose start

# Remover os containers (mantém os dados, apaga containers)
docker compose down

# Remover containers E apagar os dados do banco
docker compose down -v

# Rodar a suíte de testes
php artisan test
# ou
./vendor/bin/phpunit

# Lint / formatação
./vendor/bin/pint

# Build de produção dos assets
npm run build

# Console interativo (Tinker)
php artisan tinker
```

---

## Estrutura do projeto

```
app/
├── Http/Controllers/
│   ├── AuthController.php        # login / registro / logout
│   ├── DashboardController.php   # painel do aluno
│   ├── PracticeController.php    # modo prática (1 questão por vez)
│   ├── ExamController.php        # simulados (start, submit, resultado)
│   ├── EssayController.php       # redações (aluno + correção do professor)
│   ├── ProfessorController.php   # alunos, banco de questões, avisos
│   ├── AdminController.php       # gestão de usuários e papéis
│   └── SettingsController.php    # perfil, senha, chave OpenAI
├── Models/
│   ├── User.php                  # role, isStudent()/isProfessor()/isAdmin()
│   ├── Question.php
│   ├── Exam.php / ExamAnswer.php
│   ├── Essay.php / EssayAnalysis.php / EssayLineComment.php
│   └── Announcement.php
├── Services/
│   ├── ExamService.php
│   ├── QuestionService.php
│   └── EssayService.php          # criação, análise por IA, análise por professor
└── Providers/

resources/
├── css/app.css                   # única folha de estilo (CSS variables, sem SCSS)
├── js/{app.js,bootstrap.js}
└── views/
    ├── landing.blade.php         # página pública
    ├── checkout.blade.php        # pagamento fictício
    ├── dashboard.blade.php
    ├── videos.blade.php
    ├── auth/{login,register}.blade.php
    ├── practice/, exam/, essay/, settings/
    ├── professor/                # alunos, questões, avisos
    ├── admin/                    # index, usuários
    └── layouts/app.blade.php     # navbar + FontAwesome + Vite

routes/
├── web.php                       # todas as rotas web
└── api.php

database/
├── migrations/
├── seeders/                      # DatabaseSeeder + seeders de questões por matéria
└── factories/
```

---

## Rotas principais

| Página | Rota | Método | Acesso |
|---|---|---|---|
| Landing | `/` | GET | Público |
| Checkout | `/checkout/{plan}` | GET | Público |
| Login / Registro | `/login`, `/register` | GET/POST | Convidado |
| Dashboard | `/dashboard` | GET | Autenticado |
| Prática | `/practice` | GET/POST | Autenticado |
| Simulado | `/exam`, `/exam/{exam}`, `/exam/{exam}/submit`, `/exam/{exam}/result` | GET/POST | Autenticado |
| Redações | `/essays`, `/essays/create`, `/essays/{essay}` | GET/POST | Autenticado |
| Configurações | `/settings` | GET/PUT | Autenticado |
| Vídeos | `/videos` | GET | Autenticado |
| Redações (professor) | `/professor/essays/{essay}/analyze` | GET/POST | Professor/Admin |
| Alunos (professor) | `/professor/students`, `/professor/students/{student}` | GET | Professor/Admin |
| Banco de questões | `/professor/questions/*` | GET/POST/PUT/DELETE | Professor/Admin |
| Avisos | `/professor/announcements` | GET/POST/DELETE | Professor/Admin |
| Administração | `/admin`, `/admin/users` | GET/PUT | Admin |

Lista completa e fluxos funcionais em [`Requirements.md`](./Requirements.md).

---

## Documentação adicional

- [`Requirements.md`](./Requirements.md) — escopo completo, schema do banco, fluxos de tela e diretrizes de UI
- [`audit.md`](./audit.md) — auditoria de segurança da aplicação (OWASP)

---

## Licença

Projeto acadêmico construído sobre o framework [Laravel](https://laravel.com), licenciado sob [MIT](https://opensource.org/licenses/MIT).
