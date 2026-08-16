#!/usr/bin/env bash
set -e

# ============================================================
#  BrainLab — automated setup (Linux / macOS / Windows)
# ============================================================

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

info()    { echo -e "  ${GREEN}✔${NC}  $1"; }
warn()    { echo -e "  ${YELLOW}⚠${NC}  $1"; }
error()   { echo -e "  ${RED}✖${NC}  $1"; }
miss()    { echo -e "  ${RED}✖${NC}  $1"; }
step()    { echo -e "\n${CYAN}${BOLD}━━━ $1 ━━━${NC}"; }
success() { echo -e "  ${GREEN}✔${NC}  ${GREEN}$1${NC}"; }

# ---------- detect OS ----------
OS="$(uname -s)"
case "$OS" in
    Linux*)           PLATFORM=linux   ;;
    Darwin*)          PLATFORM=mac     ;;
    MINGW*|MSYS*|CYGWIN*) PLATFORM=windows ;;
    *)                error "Unsupported OS: $OS"; exit 1 ;;
esac

echo ""
echo -e "${GREEN}${BOLD}"
echo "  ╔══════════════════════════════════════╗"
echo "  ║     🧠 BrainLab — Project Setup     ║"
echo "  ╚══════════════════════════════════════╝"
echo -e "${NC}"
echo -e "  📦 Platform: ${BOLD}$PLATFORM${NC}"
echo ""

# ---------- helper ----------
has() { command -v "$1" &>/dev/null; }

MISSING=0

# ==========================================================
#  1. Check / install dependencies
# ==========================================================
step "🔍 Checking dependencies"

# --- Docker ---
if has docker; then
    info "Docker found: $(docker --version) 🐳"
else
    miss "Docker"
    if [[ "$PLATFORM" == "windows" ]]; then
        echo "        Download: https://www.docker.com/products/docker-desktop/"
    elif [[ "$PLATFORM" == "linux" ]]; then
        echo "        Install: https://docs.docker.com/engine/install/"
    elif [[ "$PLATFORM" == "mac" ]]; then
        echo "        Install: brew install --cask docker"
    fi
    MISSING=1
fi

# --- Docker Compose ---
if docker compose version &>/dev/null; then
    info "Docker Compose found 🐳"
else
    miss "Docker Compose (included with Docker Desktop)"
    MISSING=1
fi

# --- PHP ---
if has php; then
    info "PHP found: $(php -v | head -1) 🐘"
else
    if [[ "$PLATFORM" == "windows" ]]; then
        # Try XAMPP / WAMP
        if [[ -f "/c/xampp/php/php.exe" ]]; then
            export PATH="/c/xampp/php:$PATH"
            info "PHP found (XAMPP) 🐘"
        elif [[ -f "/c/wamp64/bin/php/php8.0.2/php.exe" ]]; then
            export PATH="/c/wamp64/bin/php/php8.0.2:$PATH"
            info "PHP found (WAMP) 🐘"
        else
            miss "PHP (>= 8.0.2)"
            echo "        Download: https://windows.php.net/download/"
            echo "        Or use XAMPP: https://www.apachefriends.org/"
            MISSING=1
        fi
    elif [[ "$PLATFORM" == "linux" ]]; then
        info "Installing PHP..."
        sudo apt-get update -qq
        sudo apt-get install -y php php-cli php-mbstring php-xml php-curl php-zip php-mysql php-bcmath unzip
    elif [[ "$PLATFORM" == "mac" ]]; then
        info "Installing PHP..."
        brew install php
    fi
fi

# --- Composer ---
if has composer; then
    info "Composer found 🎼"
else
    if [[ "$PLATFORM" == "windows" ]]; then
        miss "Composer"
        echo "        Download: https://getcomposer.org/download/"
        MISSING=1
    else
        info "Installing Composer..."
        php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
        php composer-setup.php --quiet
        rm composer-setup.php
        sudo mv composer.phar /usr/local/bin/composer
    fi
fi

# --- Node.js ---
if has node; then
    info "Node.js found: $(node -v) 🟢"
else
    if [[ "$PLATFORM" == "windows" ]]; then
        miss "Node.js (>= 16)"
        echo "        Download: https://nodejs.org/"
        MISSING=1
    elif [[ "$PLATFORM" == "linux" ]]; then
        info "Installing Node.js..."
        curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
        sudo apt-get install -y nodejs
    elif [[ "$PLATFORM" == "mac" ]]; then
        info "Installing Node.js..."
        brew install node
    fi
fi

# --- Abort if missing tools ---
if [[ "$MISSING" -eq 1 ]]; then
    echo ""
    echo -e "  ${RED}${BOLD}╔══════════════════════════════════════════╗${NC}"
    echo -e "  ${RED}${BOLD}║  ✖ Install the missing tools above,     ║${NC}"
    echo -e "  ${RED}${BOLD}║    then run this script again.           ║${NC}"
    echo -e "  ${RED}${BOLD}╚══════════════════════════════════════════╝${NC}"
    exit 1
fi

echo ""
success "All dependencies found! 🎉"

# ==========================================================
#  2. PHP dependencies
# ==========================================================
step "📦 PHP dependencies"
if [[ -d "vendor" ]]; then
    info "vendor/ already exists — skipping composer install"
else
    composer install --no-interaction --prefer-dist
    info "PHP dependencies installed"
fi

# ==========================================================
#  3. JS dependencies
# ==========================================================
step "📦 JS dependencies"
if [[ -d "node_modules" ]]; then
    info "node_modules/ already exists — skipping npm install"
else
    npm install
    info "JS dependencies installed"
fi

# ==========================================================
#  4. Environment file
# ==========================================================
step "⚙️  Configuring environment"
if [[ ! -f .env ]]; then
    cp .env.example .env
    info ".env created from .env.example 📄"
else
    info ".env already exists — skipping copy 📄"
fi

# Set DB config for Docker MySQL
sed -i.bak "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" .env
sed -i.bak "s/^# *DB_HOST=.*/DB_HOST=127.0.0.1/" .env
sed -i.bak "s/^DB_HOST=.*/DB_HOST=127.0.0.1/" .env
sed -i.bak "s/^# *DB_PORT=.*/DB_PORT=3306/" .env
sed -i.bak "s/^DB_PORT=.*/DB_PORT=3306/" .env
sed -i.bak "s/^# *DB_DATABASE=.*/DB_DATABASE=brainlab/" .env
sed -i.bak "s/^DB_DATABASE=.*/DB_DATABASE=brainlab/" .env
sed -i.bak "s/^# *DB_USERNAME=.*/DB_USERNAME=brainlab/" .env
sed -i.bak "s/^DB_USERNAME=.*/DB_USERNAME=brainlab/" .env
sed -i.bak "s/^# *DB_PASSWORD=.*/DB_PASSWORD=brainlab/" .env
sed -i.bak "s/^DB_PASSWORD=.*/DB_PASSWORD=brainlab/" .env
sed -i.bak "s/^APP_NAME=.*/APP_NAME=BrainLab/" .env
rm -f .env.bak

info "Database config set for Docker MySQL 🐬"

# ==========================================================
#  5. Start Docker containers (MySQL + phpMyAdmin)
# ==========================================================
step "🐳 Starting Docker containers"

# Check if Docker daemon is running
if ! docker info &>/dev/null; then
    error "Docker is not running!"
    echo -e "      ${YELLOW}Start Docker Desktop and run this script again.${NC}"
    exit 1
fi

docker compose up -d
info "MySQL container running on port 3306 🐬"
info "phpMyAdmin running on http://localhost:8080 🖥️"

# Wait for MySQL to be ready
echo -e "  ${YELLOW}⏳${NC}  Waiting for MySQL to be ready..."
RETRIES=30
until docker compose exec mysql mysqladmin ping -h localhost -u root -proot --silent &>/dev/null; do
    RETRIES=$((RETRIES - 1))
    if [[ "$RETRIES" -le 0 ]]; then
        error "MySQL did not start in time. Check: docker compose logs mysql"
        exit 1
    fi
    sleep 1
done
info "MySQL is ready! 🐬"

# ==========================================================
#  6. Laravel setup
# ==========================================================
step "🚀 Laravel setup"
php artisan key:generate --ansi
info "App key generated 🔑"
php artisan migrate --force
info "Migrations complete 📋"
php artisan db:seed --force 2>/dev/null && info "Database seeded 🌱" || warn "No seeders found — skipping."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
info "Caches cleared 🧹"

# ==========================================================
#  7. Start the servers
# ==========================================================
echo ""
echo -e "${GREEN}${BOLD}"
echo "  ╔══════════════════════════════════════╗"
echo "  ║    🎉 BrainLab setup complete! 🎉   ║"
echo "  ╚══════════════════════════════════════╝"
echo -e "${NC}"
echo -e "  ${CYAN}🌐 App        → ${BOLD}http://localhost:8000${NC}"
echo -e "  ${CYAN}⚡ Vite       → ${BOLD}http://localhost:5173${NC}"
echo -e "  ${CYAN}🖥️  phpMyAdmin → ${BOLD}http://localhost:8080${NC}"
echo -e "  ${CYAN}🐬 MySQL      → ${BOLD}localhost:3306${NC}  (user: brainlab / pass: brainlab)"
echo ""
echo -e "${GREEN}${BOLD}"
echo "  ╔══════════════════════════════════════════════════╗"
echo "  ║           🔑 Test Account Credentials           ║"
echo "  ╠══════════════════════════════════════════════════╣"
echo "  ║  Role       │ Email                  │ Password  ║"
echo "  ║─────────────────────────────────────────────────║"
echo "  ║  Admin      │ admin@brainlab.com     │ password  ║"
echo "  ║  Professor  │ prof@brainlab.com      │ password  ║"
echo "  ╚══════════════════════════════════════════════════╝"
echo -e "${NC}"
echo -e "  ${YELLOW}⚠${NC}  Change these passwords after first login!"
echo ""

npm run dev &
VITE_PID=$!
trap "kill $VITE_PID 2>/dev/null" EXIT

php artisan serve
