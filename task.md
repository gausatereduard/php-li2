# 📄 Техническое задание

## Проект: Веб-приложение «Finance Manager»

---

## 1. Общая информация

**Цель:** разработка веб-приложения для учета личных финансов: кошельки, счета, транзакции, планирование бюджета.

**Тип:** традиционное веб-приложение (SSR + backend на PHP)

**Технологии:**

* Backend: PHP 8+
* Frontend: PHP + HTML + CSS + JS
* БД: PostgreSQL (Docker, localhost:5432)
* Архитектура: MVC

---

## 2. Основной функционал

### Ключевые сущности:

* Пользователь (User)
* Кошелек / Счет (Wallet)
* Транзакция (Transaction)
* Категория (Category)
* Бюджет (Budget)

---

## 3. Аутентификация пользователей

### 3.1 Регистрация

Поля:

* username
* email
* password
* confirm_password

Требования:

* Уникальность username/email
* Хеширование пароля (`password_hash`)
* Валидация:

  * frontend + backend

---

### 3.2 Авторизация

* Вход по email/username + password
* Проверка через `password_verify`
* Создание сессии

---

### 3.3 Выход

* Уничтожение сессии

---

### 3.4 (Доп.) Восстановление пароля

* Генерация токена
* Таблица password_resets
* Имитация email

---

## 4. Общедоступный компонент

Страница: `/`

Содержимое:

* Общая информация о сервисе
* Пример статистики (фиктивной или агрегированной):

  * "Пользователи контролируют расходы"
  * "Сэкономлено X денег"
* 2–3 динамических блока:

  * последние зарегистрированные пользователи
  * последние публичные категории

⚠️ Данные обязательно из БД

---

## 5. Сущность: Кошелек (Wallet)

### Поля:

* id
* name (например: "Основной счет")
* balance (decimal)
* currency (USD, EUR, MDL)
* user_id
* created_at

---

## 6. Сущность: Категория

### Поля:

* id
* name (еда, транспорт, зарплата и т.д.)
* type (income / expense)
* user_id (nullable — глобальные категории)

---

## 7. Сущность: Транзакция

### Поля:

* id
* amount (decimal)
* type (income / expense / transfer)
* category_id
* wallet_id
* description
* date
* created_at

---

## 8. Форма создания ресурса (ОБЯЗАТЕЛЬНАЯ)

Страница: `/transactions/create`

### Поля (минимум 5):

* amount (number)
* type (select: income/expense/transfer)
* category (select)
* wallet (select)
* date (date picker)
* description (textarea)

---

### Требования:

* Валидация:

  * amount > 0
  * обязательные поля
  * корректная дата
* Ошибки:

  * отображаются под полями
* Данные сохраняются при ошибке

---

## 9. Бизнес-логика транзакций

При создании:

### Если:

* income → увеличивается баланс кошелька
* expense → уменьшается баланс
* transfer →

  * уменьшается один кошелек
  * увеличивается другой

⚠️ Проверка:

* нельзя списать больше, чем есть (опционально)

---

## 10. Форма поиска

Страница: `/transactions/search`

### Возможности:

* поиск по description
* фильтры:

  * по типу (income/expense)
  * по категории
  * по кошельку
  * по диапазону дат
  * по сумме

### Вывод:

* список транзакций
* сортировка (дата, сумма)
* пагинация

---

## 11. Защищённый компонент

Только для авторизованных пользователей:

### Функции:

* просмотр своих кошельков
* CRUD кошельков
* CRUD транзакций
* CRUD категорий

---

## 12. Планирование бюджета (важная часть)

### Сущность: Budget

Поля:

* id
* category_id
* limit_amount
* period (monthly)
* user_id

---

### Логика:

* пользователь задаёт лимит на категорию (например: еда — 3000 MDL)
* система считает:

  * сколько потрачено
  * сколько осталось

---

### UI:

* прогресс-бар:

  * 0–70% — зелёный
  * 70–100% — жёлтый
  * > 100% — красный

---

## 13. Роль администратора

Роль: `admin`

### Возможности (3–7 функций):

1. Просмотр всех пользователей
2. Удаление пользователей
3. Назначение ролей
4. Создание админов
5. Просмотр всех транзакций
6. Удаление любых данных
7. Управление глобальными категориями

---

## 14. База данных

### users

```
id SERIAL
username VARCHAR
email VARCHAR
password_hash TEXT
role VARCHAR
created_at TIMESTAMP
```

---

### wallets

```
id SERIAL
name VARCHAR
balance DECIMAL
currency VARCHAR(10)
user_id INT
created_at TIMESTAMP
```

---

### categories

```
id SERIAL
name VARCHAR
type VARCHAR
user_id INT NULL
```

---

### transactions

```
id SERIAL
amount DECIMAL
type VARCHAR
category_id INT
wallet_id INT
description TEXT
date DATE
created_at TIMESTAMP
```

---

### budgets

```
id SERIAL
category_id INT
limit_amount DECIMAL
period VARCHAR
user_id INT
```

---

## 15. Безопасность

Обязательно:

* Prepared statements (PDO)
* XSS защита (`htmlspecialchars`)
* CSRF токены
* Валидация всех данных
* Сессии:

  * `session_start()`
  * `session_regenerate_id()`

Пароли:

* только `password_hash`

---

## 16. API маршруты

```
POST   /register
POST   /login
POST   /logout

GET    /wallets
POST   /wallets
PUT    /wallets/{id}
DELETE /wallets/{id}

GET    /transactions
POST   /transactions
PUT    /transactions/{id}
DELETE /transactions/{id}

GET    /transactions/search
```

---

## 17. UI/UX

Навигация:

* Главная
* Вход / Регистрация
* Кошельки
* Транзакции
* Бюджет
* Админ-панель

---

## 18. Docker

```yaml
services:
  app:
    build: .
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html

  db:
    image: postgres:15
    environment:
      POSTGRES_DB: finance_db
      POSTGRES_USER: user
      POSTGRES_PASSWORD: password
    ports:
      - "5432:5432"
```

---

## 19. Документация кода

Каждый метод:

```php
/**
 * Создает транзакцию
 *
 * @param array $data
 * @return bool
 */
```

---

## 20. Критерии готовности

✅ Аутентификация работает
✅ Есть защищённые страницы
✅ Есть форма создания (транзакции)
✅ Есть форма поиска
✅ Реализованы кошельки и транзакции
✅ Есть бюджетирование
✅ Есть админ
✅ PostgreSQL + Docker
✅ MVC

---

## 21. Возможные улучшения

* графики расходов (Chart.js)
* экспорт в CSV
* мультивалютность с курсами
* уведомления о превышении бюджета
