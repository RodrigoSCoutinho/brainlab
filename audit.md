# BrainLab — Security Audit

**Date:** April 18, 2026  
**Scope:** Full application codebase (controllers, services, models, views, routes, configuration)  
**Framework:** Laravel 9 (PHP 8.0)

---

## Summary

| Severity | Count |
|----------|-------|
| Critical | 1 (fixed) |
| Medium   | 1 (fixed) |
| Low      | 1 |
| Info     | 1 |

---

## Issues Found

### 1. CRITICAL — Login bypassed authentication (FIXED)

**File:** `app/Http/Controllers/AuthController.php`  
**OWASP Category:** A07:2021 — Identification and Authentication Failures

**Before (vulnerable):**
```php
// DEV MODE: auto-create user and log in (remove for production)
$user = User::firstOrCreate(
    ['email' => $request->email],
    [
        'name' => explode('@', $request->email)[0],
        'password' => Hash::make($request->password),
    ]
);
Auth::login($user, $request->boolean('remember'));
```

**Problem:** The login method used `firstOrCreate` — typing any existing user's email with any password logged you in as that user. New accounts were also created without going through registration. This allowed trivial **account takeover**.

**Fix applied:** Replaced with `Auth::attempt()`, which properly verifies the password against the stored hash. Failed attempts return an error message.

```php
if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
    return back()->withErrors([
        'email' => 'As credenciais informadas não conferem.',
    ])->onlyInput('email');
}
$request->session()->regenerate();
```

---

### 2. MEDIUM — `role` was mass-assignable on User model (FIXED)

**File:** `app/Models/User.php`  
**OWASP Category:** A01:2021 — Broken Access Control

**Before (vulnerable):**
```php
protected $fillable = ['name', 'email', 'password', 'role'];
```

**Problem:** With `role` in the `$fillable` array, any form submission or future endpoint that passes user input to `User::create()` or `$user->update()` could set `role` to `professor`, escalating privileges. While no current endpoint directly exposed this, it is a common source of privilege escalation bugs as the codebase evolves.

**Fix applied:** Removed `role` from `$fillable`. To assign roles, use explicit assignment:
```php
$user->role = 'professor';
$user->save();
```

---

### 3. LOW — No rate limiting on authentication routes

**Files:** `routes/web.php`  
**OWASP Category:** A07:2021 — Identification and Authentication Failures

**Problem:** The `/login` and `/register` POST routes have no throttling. An attacker could brute-force credentials or spam account creation.

**Recommendation:** Apply Laravel's built-in `throttle` middleware:
```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});
```
This limits to 5 attempts per minute per IP.

---

### 4. INFO — `APP_DEBUG=true` in `.env`

**File:** `.env`  
**OWASP Category:** A05:2021 — Security Misconfiguration

**Problem:** When `APP_DEBUG=true`, Laravel displays detailed error pages with stack traces, database credentials, environment variables, and file paths. This is expected during development but **must be set to `false` before deploying to production**.

**Recommendation:**
```env
APP_DEBUG=false
```

---

## What Passed (No Issues)

### SQL Injection — PASS
- All database queries use Eloquent ORM (parameterized by default).
- No `DB::raw()`, `whereRaw()`, `selectRaw()`, or raw SQL found anywhere in application code.

### Cross-Site Scripting (XSS) — PASS
- All Blade templates use `{{ }}` (auto-escaped output).
- No `{!! !!}` (unescaped output) found in any application template.
- User input (essay content, names, titles) is always escaped on render.

### Cross-Site Request Forgery (CSRF) — PASS
- All POST forms include `@csrf` tokens.
- `VerifyCsrfToken` middleware is active with an empty `$except` array (no routes excluded).

### Session Security — PASS
- Session is regenerated on login (`$request->session()->regenerate()`).
- Session is invalidated on logout (`$request->session()->invalidate()`).
- CSRF token is regenerated on logout (`$request->session()->regenerateToken()`).
- Cookie settings: `http_only: true`, `same_site: lax`.

### Password Handling — PASS
- Passwords are hashed with `Hash::make()` (bcrypt) before storage.
- Passwords are never logged, displayed, or returned in responses.
- Registration requires minimum 8 characters with confirmation.

### Authorization / Access Control — PASS
- Exam routes check `$exam->user_id !== Auth::id()` → `abort(403)`.
- Essay routes check `$essay->user_id !== $user->id` → `abort(403)`.
- Professor routes check `Auth::user()->role !== 'professor'` → `abort(403)`.
- All authenticated routes are behind the `auth` middleware group.
- Guest-only routes (login/register) use the `guest` middleware.

### Input Validation — PASS
- All form submissions use `$request->validate()` with explicit rules.
- Types, lengths, and allowed values are enforced (e.g., `'in:a,b,c,d'`, `'min:0', 'max:200'`).
- `question_id` is validated with `'exists:questions,id'`.

### Insecure Direct Object References (IDOR) — PASS
- Exam, essay, and essay analysis access is scoped to the authenticated user.
- Route model binding with ownership checks prevents accessing other users' resources.

---

## Files Audited

| File | Status |
|------|--------|
| `app/Http/Controllers/AuthController.php` | Fixed |
| `app/Http/Controllers/DashboardController.php` | Clean |
| `app/Http/Controllers/EssayController.php` | Clean |
| `app/Http/Controllers/ExamController.php` | Clean |
| `app/Http/Controllers/PracticeController.php` | Clean |
| `app/Services/EssayService.php` | Clean |
| `app/Services/ExamService.php` | Clean |
| `app/Services/QuestionService.php` | Clean |
| `app/Models/User.php` | Fixed |
| `app/Models/Essay.php` | Clean |
| `app/Models/Exam.php` | Clean |
| `app/Models/ExamAnswer.php` | Clean |
| `routes/web.php` | Advisory |
| `config/session.php` | Clean |
| `.env` | Advisory |
| All `resources/views/**/*.blade.php` | Clean |
