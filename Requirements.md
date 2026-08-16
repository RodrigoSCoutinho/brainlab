# BrainLab — IFRN Prep Platform (TCC Project)

---

## 1. Objective

Build a web platform to help **9º ano** students prepare for the **IFRN entrance exam** (ensino médio) through practice questions, simulated tests, essay analysis (by AI and professors), and curated video lessons — all organized by topic.

---

## 2. Project Scope

This is an academic project (TCC). The focus is:

- Fully functional system with multiple study modes
- Professional, modern UI using FontAwesome icons (no images)
- IFRN institutional color scheme (green #006633 / red #cc0000)
- Role-based features for students and professors
- Fake payment page for demonstration purposes

**Out of scope:**

- Real payment processing
- Real AI API integration (simulated scoring)
- Mobile app
- Complex analytics

---

## 3. Tech Stack & Versions

| Technology       | Version / Detail                  |
| ---------------- | --------------------------------- |
| **PHP**          | ^8.0.2                            |
| **Laravel**      | ^9.19 (Laravel 9)                 |
| **Sanctum**      | ^3.0 (API token auth)             |
| **Tinker**       | ^2.7                              |
| **Database**     | MySQL 8.0 (Docker)                |
| **DB Admin**     | phpMyAdmin (Docker)               |
| **Containers**   | Docker Compose                    |
| **Frontend**     | Blade + Plain CSS (CSS variables) |
| **Icons**        | FontAwesome 6.5.1 (CDN)           |
| **Build Tool**   | Vite ^4.0.0                       |
| **Vite Plugin**  | laravel-vite-plugin ^0.7.2        |
| **JS Libraries** | Axios ^1.1.2, Lodash ^4.17.19    |
| **PostCSS**      | ^8.1.14                           |
| **Testing**      | PHPUnit ^9.5.10                   |
| **Linting**      | Laravel Pint ^1.0                 |
| **Mocking**      | Mockery ^1.4.4                    |
| **Faker**        | FakerPHP ^1.9.1                   |
| **Error Page**   | Spatie Laravel Ignition ^1.0      |
| **HTTP Client**  | Guzzle ^7.2                       |

---

## 4. UI / Design Guidelines

### Colors (IFRN Institutional)

| Token                | Hex / Value                         |
| -------------------- | ----------------------------------- |
| Primary Green        | `#006633` (--ifrn-green)            |
| Dark Green           | `#004d26` (--ifrn-green-dark)       |
| Light Green          | `#2e8b57` (--ifrn-green-light)      |
| Institutional Red    | `#cc0000` (--ifrn-red)              |
| Background Light     | `#f4f6f8` (--bg-light)              |
| Text Dark            | `#1a1a1a` (--text-dark)             |
| Text Muted           | `#6c757d` (--text-muted)            |

### Layout & Design

- All styling via **plain CSS** with CSS custom properties (no SCSS)
- Single stylesheet: `resources/css/app.css`
- Custom smooth scrollbar (react-scrollbar style)
- FontAwesome icons everywhere — **no images** used
- SVG favicon with brain emoji on green circle (`public/favicon.svg`)
- Fully responsive (desktop, tablet, mobile breakpoints)
- Scroll-reveal animations on landing page (IntersectionObserver)

### Branding

- Brand: **BrainLab** with `fa-solid fa-brain` icon
- Tab title: "BrainLab — Plataforma Inteligente"
- IFRN green theme across all buttons, links, and highlights

---

## 5. User Roles

| Role        | Default | Capabilities                                                |
| ----------- | ------- | ----------------------------------------------------------- |
| **student** | Yes     | Practice, Simulados, Essays (submit + AI analysis), Videos  |
| **professor** | No    | All student features + Correct student essays with grading  |

- Role stored in `users.role` column (string: `'student'` or `'professor'`)
- `User::isProfessor()` method for authorization checks
- Professor nav link only shown when `isProfessor()` is true

---

## 6. Core Features

### 6.1 Landing Page (`/`)

- Standalone page (does not extend layouts.app)
- Fixed navbar with scroll transparency effect
- Hero section with animated floating shapes and gradient background
- **Features grid**: 6 cards (Simulados, Prática, Redações, IA, Painel Professor, Dashboard)
- **How it works**: 3-step guide (Crie conta → Escolha → Acompanhe)
- **Pricing plans**: 3 cards (Estudante Grátis, Pro R$19/mês, Professor R$39/mês)
- Footer with links
- Scroll-reveal animations via IntersectionObserver
- Smooth anchor scrolling
- Redirects to `/dashboard` if user is already authenticated

### 6.2 Authentication

- User registration with name, email, password
- Login / Logout
- Powered by Laravel Sanctum
- Guest middleware on auth routes
- Auth middleware on all app routes

### 6.3 Practice Mode (`/practice`)

- Show one question at a time
- Multiple choice (A, B, C, D)
- Immediate feedback after answering (correct/incorrect + explanation)

### 6.4 Simulado — Mock Exam (`/exam`)

- 10–20 random questions per exam
- Timer (JavaScript countdown)
- Submit all answers at once
- Final score displayed with answer review
- Exam history on index page

### 6.5 Dashboard (`/dashboard`)

- List of previous exam scores
- Percentage of correct answers
- Stats grid with key metrics
- Quick action links

### 6.6 Essays / Redações (`/essays`)

**Student features:**
- Submit essays with title, subject, and content
- View submitted essays and their status
- Request **AI analysis** (simulated — scores based on word count/paragraphs)
- View competency scores (5 ENEM competencies, 0–200 each, max 1000 total)
- View professor feedback when available

**Professor features (`/professor/essays`):**
- Dashboard showing pending essays and all submitted essays
- Analyze/correct student essays with:
  - Individual score per competency (1–5, each 0–200)
  - Written feedback textarea
- Authorization check via `User::isProfessor()`

**ENEM 5-Competency Scoring:**
| # | Competency                                        | Max Score |
|---|---------------------------------------------------|-----------|
| 1 | Domínio da norma culta                            | 200       |
| 2 | Compreensão da proposta                           | 200       |
| 3 | Seleção e organização de informações              | 200       |
| 4 | Conhecimento dos mecanismos linguísticos           | 200       |
| 5 | Proposta de intervenção                            | 200       |

### 6.7 Video Lessons (`/videos`)

- Curated YouTube video links organized by topic
- **10 topics** relevant to IFRN 9º ano → 1º ensino médio:

**Matemática:**
| Topic                  | Icon              |
|------------------------|-------------------|
| Porcentagem            | fa-percent        |
| Frações                | fa-divide         |
| Equações               | fa-superscript    |
| Geometria              | fa-shapes         |
| Razão e Proporção      | fa-scale-balanced |
| Estatística            | fa-chart-bar      |
| Potenciação/Radiciação | fa-arrow-up-1-9   |

**Português:**
| Topic                  | Icon              |
|------------------------|-------------------|
| Interpretação de Texto | fa-book-open      |
| Gramática              | fa-spell-check    |
| Redação                | fa-pen-fancy      |

- 20 videos total (2 per topic)
- Channels: Prof. Ferretto, Matemática Rio, Prof. Noslen, Brasil Escola
- Video cards with **colored gradient thumbnails** per topic (no external images)
- Topic icon displayed in thumbnail area
- Play button overlay + duration badge
- Client-side topic filter (show/hide with JavaScript)
- Click opens YouTube in new tab

### 6.8 Fake Payment / Checkout (`/checkout/{plan}`)

- Accessed from landing page "Assinar agora" buttons
- Two plans: `pro` and `professor`
- **3 payment methods** (all fake/demonstration):
  - **PIX**: Fake QR code icon, copyable PIX code, countdown timer (30 min)
  - **Credit Card**: Form with masked inputs (card number, expiry MM/YY, CVV, CPF)
  - **Boleto**: Fake barcode number, generate button
- Order summary sidebar (plan details, features, price breakdown)
- Security badges (encrypted, cancel anytime)
- **Success modal** with animation after "paying" → links to dashboard
- Tab switching between payment methods
- Input formatting (card number groups of 4, CPF mask, expiry MM/YY)
- All for demonstration — no real payment processing

---

## 7. Database Structure

### Table: `users`

| Column     | Type            |
| ---------- | --------------- |
| id         | bigint (PK)     |
| name       | string          |
| email      | string (unique) |
| password   | string          |
| role       | string (default: 'student') |
| created_at | timestamp       |
| updated_at | timestamp       |

### Table: `questions`

| Column         | Type        |
| -------------- | ----------- |
| id             | bigint (PK) |
| statement      | text        |
| option_a       | string      |
| option_b       | string      |
| option_c       | string      |
| option_d       | string      |
| correct_option | char(1)     |
| subject        | string      |

### Table: `exams`

| Column     | Type        |
| ---------- | ----------- |
| id         | bigint (PK) |
| user_id    | FK → users  |
| score      | integer     |
| created_at | timestamp   |

### Table: `exam_answers`

| Column          | Type           |
| --------------- | -------------- |
| id              | bigint (PK)    |
| exam_id         | FK → exams     |
| question_id     | FK → questions |
| selected_option | char(1)        |
| is_correct      | boolean        |

### Table: `essays`

| Column     | Type                                          |
| ---------- | --------------------------------------------- |
| id         | bigint (PK)                                   |
| user_id    | FK → users                                    |
| title      | string                                        |
| content    | text                                          |
| subject    | string (nullable)                             |
| status     | string (default: 'submitted', then 'analyzed')|
| created_at | timestamp                                     |
| updated_at | timestamp                                     |

### Table: `essay_analyses`

| Column        | Type                       |
| ------------- | -------------------------- |
| id            | bigint (PK)                |
| essay_id      | FK → essays                |
| analyzed_by   | FK → users (nullable)      |
| analysis_type | string ('ai' or 'professor') |
| score         | integer (nullable)         |
| feedback      | text (nullable)            |
| competency_1  | integer (0–200)            |
| competency_2  | integer (0–200)            |
| competency_3  | integer (0–200)            |
| competency_4  | integer (0–200)            |
| competency_5  | integer (0–200)            |
| created_at    | timestamp                  |
| updated_at    | timestamp                  |

---

## 8. Data Source

- Questions extracted from past IFRN exams (PDF)
- Additional questions generated using AI (Claude)
- Stored in JSON and seeded into the database via Laravel seeders
- Video links curated from popular Brazilian education YouTube channels

---

## 9. Functional Flows

### Practice Flow

1. User selects "Praticar"
2. System shows a random question
3. User answers
4. System shows correct answer + explanation

### Exam Flow

1. User starts exam
2. System selects random questions
3. Timer starts (JavaScript countdown)
4. User submits answers
5. System calculates score
6. Results displayed with answer review

### Essay Flow (Student)

1. Student navigates to "Redações" → "Nova Redação"
2. Fills in title, subject, and essay content
3. Submits essay (status: `submitted`)
4. Can request AI analysis (simulated scoring)
5. Views competency scores and feedback

### Essay Flow (Professor)

1. Professor navigates to "Professor" panel
2. Sees list of pending essays from students
3. Opens an essay → fills in 5 competency scores + feedback
4. Submits analysis (status changes to `analyzed`)

### Checkout Flow (Demonstration)

1. User clicks "Assinar agora" on a pricing plan
2. Redirected to `/checkout/pro` or `/checkout/professor`
3. Chooses payment method (PIX, Card, or Boleto)
4. Fills fake form or copies fake PIX code
5. Clicks pay → success modal appears
6. Clicks "Ir para Dashboard"

---

## 10. Pages & Routes

| Page                | Route                            | Method | Auth    | Description                          |
| ------------------- | -------------------------------- | ------ | ------- | ------------------------------------ |
| Landing             | `/`                              | GET    | Guest   | Marketing page with plans            |
| Checkout            | `/checkout/{plan}`               | GET    | Public  | Fake payment page (pro/professor)    |
| Login               | `/login`                         | GET/POST | Guest | Email + password form                |
| Register            | `/register`                      | GET/POST | Guest | Name, email, password form           |
| Dashboard           | `/dashboard`                     | GET    | Auth    | Scores overview, quick actions       |
| Practice            | `/practice`                      | GET/POST | Auth  | One question at a time               |
| Exam Start          | `/exam`                          | GET/POST | Auth  | Start a new mock exam                |
| Exam In-Progress    | `/exam/{exam}`                   | GET    | Auth    | Questions + timer                    |
| Exam Submit         | `/exam/{exam}/submit`            | POST   | Auth    | Submit answers                       |
| Exam Result         | `/exam/{exam}/result`            | GET    | Auth    | Score and answer review              |
| Essays Index        | `/essays`                        | GET    | Auth    | Student essay list                   |
| Essay Create        | `/essays/create`                 | GET    | Auth    | Essay submission form                |
| Essay Store         | `/essays`                        | POST   | Auth    | Save new essay                       |
| Essay Show          | `/essays/{essay}`                | GET    | Auth    | View essay + analysis                |
| Essay AI Analyze    | `/essays/{essay}/analyze-ai`     | POST   | Auth    | Request AI analysis                  |
| Professor Essays    | `/professor/essays`              | GET    | Auth*   | Professor dashboard                  |
| Professor Analyze   | `/professor/essays/{essay}/analyze` | GET/POST | Auth* | Grade student essay               |
| Videos              | `/videos`                        | GET    | Auth    | Video lessons by topic               |

*Auth + `isProfessor()` check

---

## 11. Architecture

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── PracticeController.php
│   │   ├── ExamController.php
│   │   └── EssayController.php
│   └── Middleware/
├── Models/
│   ├── User.php              # + role, isProfessor(), essays()
│   ├── Question.php
│   ├── Exam.php
│   ├── ExamAnswer.php
│   ├── Essay.php             # user(), analyses(), latestAnalysis()
│   └── EssayAnalysis.php     # essay(), analyzer(), totalScore()
├── Services/
│   ├── ExamService.php
│   ├── QuestionService.php
│   └── EssayService.php      # create, analyzeAI, analyzeProfessor, getEssays
└── Providers/

resources/
├── css/
│   └── app.css               # Single CSS file with all styles
├── js/
│   ├── app.js
│   └── bootstrap.js
└── views/
    ├── landing.blade.php      # Standalone landing page
    ├── checkout.blade.php     # Fake payment page
    ├── dashboard.blade.php
    ├── videos.blade.php       # Video lessons page
    ├── welcome.blade.php
    ├── auth/
    │   ├── login.blade.php
    │   └── register.blade.php
    ├── essay/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── show.blade.php
    │   ├── professor-index.blade.php
    │   └── professor-analyze.blade.php
    ├── exam/
    ├── layouts/
    │   └── app.blade.php      # Main layout (navbar, FontAwesome, Vite)
    └── practice/

routes/
├── web.php                    # All web routes (public + auth + videos + checkout)
└── api.php

database/
├── migrations/
│   ├── create_users_table
│   ├── create_questions_table
│   ├── create_exams_table
│   ├── create_exam_answers_table
│   ├── add_role_to_users_table
│   ├── create_essays_table
│   └── create_essay_analyses_table
├── seeders/
└── factories/

public/
├── favicon.svg                # SVG brain icon favicon
└── index.php
```

**Principles:**

- Controllers handle HTTP requests
- Services encapsulate business logic (`ExamService`, `QuestionService`, `EssayService`)
- Blade templates kept clean with layouts
- All CSS in one file using CSS custom properties
- No SCSS — plain CSS only (Vite has no SCSS plugin)
- FontAwesome for all icons — zero images

---

## 12. Optional Enhancements (if time allows)

- Show explanation for each question
- Track weak subjects per student
- Retry incorrect questions
- Real AI integration for essay analysis (e.g., Claude API)
- Real payment gateway integration
- Notification system for professor corrections

---

## 13. Automated Setup

### Prerequisites

- **Docker Desktop** — [download](https://www.docker.com/products/docker-desktop/)
- **PHP** >= 8.0.2
- **Composer**
- **Node.js** >= 16

### One-command setup

A single `setup.sh` script handles **everything** on all platforms: Linux, macOS, and Windows (via Git Bash).

```bash
chmod +x setup.sh
./setup.sh
```

> **On Windows:** open **Git Bash** (comes with Git for Windows), navigate to the project folder, and run `./setup.sh`.

### What the script does

| Step | Action                                                     |
| ---- | ---------------------------------------------------------- |
| 1    | Detect OS (Linux / macOS / Windows)                        |
| 2    | Check / install Docker, PHP, Composer, Node.js             |
| 3    | `composer install` (PHP dependencies)                      |
| 4    | `npm install` (JS dependencies)                            |
| 5    | Copy `.env.example` → `.env` and configure DB for Docker   |
| 6    | `docker compose up -d` (MySQL 8.0 + phpMyAdmin)            |
| 7    | Wait for MySQL to be healthy                               |
| 8    | `php artisan key:generate`                                 |
| 9    | `php artisan migrate`                                      |
| 10   | `php artisan db:seed`                                      |
| 11   | Clear config/cache/view caches                             |
| 12   | Start Laravel server (`:8000`) + Vite (`:5173`)            |

### Services after setup

| Service      | URL                        | Credentials              |
| ------------ | -------------------------- | ------------------------ |
| App          | http://localhost:8000      | —                        |
| Vite         | http://localhost:5173      | —                        |
| phpMyAdmin   | http://localhost:8080      | root / root              |
| MySQL        | localhost:3306             | brainlab / brainlab      |

### Docker commands

```bash
# Start containers
docker compose up -d

# Stop containers (data is preserved)
docker compose stop

# Restart containers
docker compose start

# Stop and remove containers (data is preserved)
docker compose down

# Stop and DELETE all data
docker compose down -v
```

---

## 14. Manual Development Commands

```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start dev server
php artisan serve

# Start Vite (frontend hot reload)
npm run dev

# Build for production
npm run build

# Run tests
php artisan test
# or
./vendor/bin/phpunit

# Code linting
./vendor/bin/pint
```