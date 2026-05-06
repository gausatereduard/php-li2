# Finance Manager — система управления личными финансами

## Описание проекта

Это веб-приложение на чистом PHP (без фреймворков) для управления личными финансами. Проект разработан в учебных целях и демонстрирует основные концепции веб-разработки: MVC-архитектура, работа с базой данных, аутентификация, маршрутизация и безопасность.

---

## Содержание

1. [Архитектура проекта](#архитектура-проекта)
2. [Структура базы данных](#структура-базы-данных)
3. [Основные компоненты](#основные-компоненты)
4. [Как это работает](#как-это-работает)
5. [Запуск проекта](#запуск-проекта)

---

## Архитектура проекта

Проект использует паттерн **MVC** (Model-View-Controller):

```
Пользователь → Router → Controller → Model → Database
                      ↓
                   View (HTML)
```

### Почему MVC?

MVC разделяет ответственность между компонентами:
- **Model** — работа с данными и бизнес-логика
- **View** — представление (HTML-шаблоны)
- **Controller** — обработка запросов и координация

Это делает код чище, удобнее для поддержки и тестирования.

---

## Структура базы данных

### Users (Пользователи)

```sql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    role VARCHAR(20) DEFAULT 'user',  -- 'user' или 'admin'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Зачем нужен `role`?** Позволяет разграничить права доступа. Администратор может управлять пользователями, обычные пользователи — только своими данными.

### Wallets (Кошельки)

```sql
CREATE TABLE wallets (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    balance DECIMAL(15,2) DEFAULT 0,
    currency VARCHAR(10) DEFAULT 'USD',
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

**Зачем нужен `ON DELETE CASCADE`?** При удалении пользователя автоматически удаляются все его кошельки — это предотвращает "orphaned" записи.

### Categories (Категории)

```sql
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(20) NOT NULL,  -- 'income' или 'expense'
    user_id INT NULL            -- NULL = глобальная категория
);
```

**Почему `user_id` может быть NULL?** Глобальные категории (например, "Зарплата", "Еда") доступны всем пользователям. Личные категории принадлежат конкретному пользователю.

### Transactions (Транзакции)

```sql
CREATE TABLE transactions (
    id SERIAL PRIMARY KEY,
    amount DECIMAL(15,2) NOT NULL,
    type VARCHAR(20) NOT NULL,       -- 'income', 'expense' или 'transfer'
    category_id INT NOT NULL,
    wallet_id INT NOT NULL,
    target_wallet_id INT NULL,      -- для переводов между кошельками
    description TEXT,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE
);
```

**Зачем нужен `target_wallet_id`?** Для операций перевода между кошельками. Если это обычный доход/расход — поле будет NULL.

### Budgets (Бюджеты)

```sql
CREATE TABLE budgets (
    id SERIAL PRIMARY KEY,
    category_id INT NOT NULL,
    limit_amount DECIMAL(15,2) NOT NULL,
    period VARCHAR(20) DEFAULT 'monthly',  -- 'monthly' или 'weekly'
    user_id INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Exchange Rates (Курсы валют)

```sql
CREATE TABLE exchange_rates (
    id SERIAL PRIMARY KEY,
    base_currency VARCHAR(3) NOT NULL,      -- 'MDL'
    target_currency VARCHAR(3) NOT NULL,   -- 'USD', 'EUR'
    rate DECIMAL(15,6) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(base_currency, target_currency)
);
```

**Зачем хранить курсы в БД?** Чтобы не делать запрос к внешнему API при каждом обновлении страницы. Достаточно обновлять раз в сутки.

---

## Основные компоненты

### 1. Маршрутизация (Router)

Файл: `config/router.php`

```php
class Router {
    private $routes = [];

    // Добавить маршрут
    public function add($method, $path, $callback) {
        $this->routes[] = ['method' => $method, 'path' => $path, 'callback' => $callback];
    }

    // Найти и выполнить маршрут
    public function dispatch($method, $path) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                return $route['callback']();
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }
}
```

**Как это работает:**
1. При запросе `/transactions` роутер ищет匹配的 маршрут
2. Находит `GET /transactions` → вызывает `TransactionController()->index()`
3. Контроллер обрабатывает запрос и возвращает представление

**Зачем нужен Router?** Централизует обработку URL, делает код чище, чем использование единого `index.php` с `switch`.

### 2. Модели (Models)

Файл: `app/models/Transaction.php`

```php
class Transaction {
    private function getPDO() {
        return getPDO();
    }

    // Создание транзакции с транзакцией БД
    public function create($data) {
        $pdo = $this->getPDO();
        $pdo->beginTransaction();  // Начало транзакции
        try {
            // Вставка транзакции
            $stmt = $pdo->prepare("INSERT INTO transactions (...) VALUES (?, ...)");
            $stmt->execute([...]);

            // Обновление баланса кошелька
            $this->applyTransaction($data, $pdo);

            $pdo->commit();  // Подтверждение транзакции
            return $pdo->lastInsertId();
        } catch (Exception $e) {
            $pdo->rollBack();  // Откат при ошибке
            return false;
        }
    }
}
```

**Зачем нужны транзакции БД?** Гарантируют целостность данных. Если обновление баланса не удастся — откатывается и создание транзакции.

### 3. Контроллеры (Controllers)

Файл: `app/controllers/TransactionController.php`

```php
class TransactionController extends BaseController {

    // Обработка создания транзакции
    public function create() {
        // 1. Проверка аутентификации
        if (!$this->isAuthenticated()) $this->redirect('/login');

        $errors = [];
        $data = $_POST;

        // 2. Валидация данных
        if (empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
            $errors['amount'] = 'Amount must be > 0';
        }

        // 3. Если нет ошибок — сохраняем
        if (empty($errors)) {
            $transactionModel = new Transaction();
            $result = $transactionModel->create($data);
            if ($result) $this->redirect('/transactions');
        }

        // 4. Рендер шаблона с ошибками
        $this->render('transactions/create', ['errors' => $errors, 'data' => $data]);
    }
}
```

**Почему валидация в контроллере?** Центральное место для проверки входящих данных перед передачей в модель.

### 4. Безопасность

#### CSRF-защита

```php
// Хелпер: генерация токена
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Валидация токена
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
```

**Зачем это нужно?** Предотвращает CSRF-атаки, когда злоумышленник отправляет форму от имени пользователя без его ведома.

#### Защита от SQL-инъекций

```php
// Используем Prepared Statements (параметризованные запросы)
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);  // Параметры подставляются безопасно
```

**Почему это важно?** Предотвращает SQL-инъекции — один из самых опасных видов атак.

#### XSS-защита

```php
// Хелпер для экранирования вывода
function esc($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// В представлении: <?= esc($username) ?>
```

**Зачем?** Предотвращает XSS-атаки, когда злоумышленник внедряет JavaScript-код через пользовательский ввод.

---

## Как это работает

### Пример: Создание транзакции

1. **Пользователь** заполняет форму на странице `/transactions/create`
2. **Форма** отправляет POST-запрос с CSRF-токеном
3. **Router**匹配 роут `POST /transactions/create`
4. **TransactionController->create()**:
   - Проверяет CSRF-токен
   - Валидирует данные (сумма > 0, категория выбрана и т.д.)
   - Вызывает `TransactionModel->create()`
5. **TransactionModel**:
   - Начинает транзакцию БД
   - Вставляет запись в таблицу `transactions`
   - Обновляет баланс кошелька (`UPDATE wallets SET balance = balance + ?`)
   - Коммитит транзакцию или откатывает при ошибке
6. **Контроллер** редиректит на список транзакций

### Пример: Отображение курсов валют

1. При загрузке главной страницы вызывается `HomeController->index()`
2. Контроллер получает курсы через `ExchangeRateModel->getOrUpdate()`
3. Модель проверяет, когда в последний раз обновлялись курсы
4. Если прошло более 24 часов — делает запрос к внешнему API:
   ```php
   $response = file_get_contents('https://api.frankfurter.app/latest?from=MDL');
   $data = json_decode($response, true);
   // Сохраняет в БД: INSERT INTO exchange_rates ... ON CONFLICT DO UPDATE
   ```
5. Шблон отображает виджет с курсами

**Почему так?** Вместо запроса к API при каждом показе страницы (медленно), мы сохраняем в БД и обновляем раз в сутки (быстро).

---

## Запуск проекта

### Требования

- PHP 8.1+
- PostgreSQL 14+
- Docker (для запуска через Docker Compose)

### Запуск через Docker Compose

```bash
# Клонировать проект
git clone <repository-url>
cd php-li2

# Запустить контейнеры
docker-compose up --build

# Открыть в браузере
http://localhost:8000
```

### Структура Docker Compose

```yaml
services:
  app:
    build: .
    ports:
      - "8000:80"
    depends_on:
      - db

  db:
    image: postgres:14
    environment:
      POSTGRES_DB: finance_db
      POSTGRES_USER: user
      POSTGRES_PASSWORD: password
```

---

## Структура файлов

```
php-li2/
├── app/
│   ├── controllers/          # Контроллеры (обработка запросов)
│   │   ├── BaseController.php
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   ├── TransactionController.php
│   │   ├── WalletController.php
│   │   ├── BudgetController.php
│   │   └── AdminController.php
│   │
│   ├── models/               # Модели (работа с данными)
│   │   ├── User.php
│   │   ├── Wallet.php
│   │   ├── Transaction.php
│   │   ├── Category.php
│   │   ├── Budget.php
│   │   └── ExchangeRate.php
│   │
│   └── views/               # Представления (HTML-шаблоны)
│       ├── layout.php       # Общий шаблон (header, footer)
│       ├── home.php
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       ├── transactions/
│       │   ├── index.php
│       │   ├── create.php
│       │   └── edit.php
│       ├── wallets/
│       └── budgets/
│
├── config/
│   ├── database.php         # Подключение к PostgreSQL
│   ├── router.php           # Маршрутизация
│   └── migrations/          # SQL-миграции для БД
│
├── public/
│   ├── index.php            # Точка входа
│   ├── css/style.css        # Стили
│   └── .htaccess            # Настройки Apache
│
└── helpers/
    └── functions.php        # Вспомогательные функции
```

---

## Основные команды

```bash
# Запуск
docker-compose up --build

# Остановка
docker-compose down

# Просмотр логов
docker-compose logs -f

# Подключение к БД
docker exec -it php-li2-db-1 psql -U user -d finance_db
```

---

## Что можно улучшить

Это учебный проект, и есть много мест для улучшения:

1. **Тесты** — сейчас нет unit-тестов
2. **Пагинация** — для больших списков транзакций
3. **API** — REST API для мобильного приложения
4. **Загрузка файлов** — экспорт/импорт данных
5. **Кэширование** — Redis для кэша сессий
6. **Логирование** — для отладки и мониторинга

---

## Авторы

Проект разработан в рамках курса веб-разработки.