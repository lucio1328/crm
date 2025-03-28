### **Créer une authentification API en parallèle de Laravel UI sur Laravel 7**  
Si tu utilises **Laravel UI** pour l'authentification côté Blade, et que tu veux également une authentification API, tu peux le faire sans conflit en utilisant **Laravel Passport** ou **Sanctum**.  

---

## **1. Installer Laravel Passport pour l'authentification API**  

Dans Laravel 7, **Passport** est la meilleure solution pour gérer l'authentification API.  

### **Étape 1 : Installer Passport**  
Dans ton projet Laravel, exécute la commande suivante :  

```sh
composer require laravel/passport
```

Ensuite, publie les fichiers de migration de Passport et exécute la migration :  

```sh
php artisan passport:install
php artisan migrate
```

La première commande va générer des clés de chiffrement et des tokens nécessaires pour l’authentification. Garde bien les **Client ID et Secret** affichés après l’installation.

---

## **2. Configurer Passport dans Laravel**  

### **Étape 2 : Ajouter Passport dans `config/auth.php`**  
Ouvre le fichier `config/auth.php` et change le **guard API** en `passport` :  

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'passport', // Remplace "token" par "passport"
        'provider' => 'users',
    ],
],
```

---

### **Étape 3 : Ajouter `HasApiTokens` dans `User.php`**  

Dans le modèle `User.php`, ajoute le trait `HasApiTokens` :

```php
namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}
```

---

## **3. Créer un contrôleur pour l'authentification API**  

Crée un contrôleur pour l'authentification API :

```sh
php artisan make:controller Api/AuthController
```

Ajoute ces méthodes dans `app/Http/Controllers/Api/AuthController.php` :

```php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Méthode de connexion
    public function login(Request $request)
    {
        // Validation des champs
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Vérification des informations
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        // Récupération de l'utilisateur
        $user = Auth::user();
        $token = $user->createToken('Token-API')->accessToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Méthode de déconnexion
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}
```

---

## **4. Ajouter les routes API**  

Dans `routes/api.php`, ajoute :

```php
use App\Http\Controllers\Api\AuthController;

// Routes API
Route::post('/login-api', [AuthController::class, 'login']);
Route::post('/logout-api', [AuthController::class, 'logout'])->middleware('auth:api');

// Routes protégées par authentification
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
});
```

---

## **5. Protéger les routes API avec Passport**  

Dans `app/Providers/AuthServiceProvider.php`, ajoute :

```php
use Laravel\Passport\Passport;

public function boot()
{
    $this->registerPolicies();
    Passport::routes();
}
```

Puis exécute :  

```sh
php artisan passport:install
```

---

## **6. Tester l'authentification API**  

### **1️⃣ Tester le login API :**  
Fais une requête `POST` sur `http://127.0.0.1:8000/api/login-api` avec :  

```json
{
    "email": "user@example.com",
    "password": "password"
}
```

👉 **Réponse attendue :**
```json
{
    "message": "Connexion réussie",
    "user": {
        "id": 1,
        "name": "User",
        "email": "user@example.com"
    },
    "token": "Bearer xxxxx"
}
```

---

### **2️⃣ Tester un accès protégé**  
Fais une requête `GET` sur `http://127.0.0.1:8000/api/user` avec **le token** dans l'en-tête `Authorization` :

```
Authorization: Bearer xxxxx
```

---

## **7. Gérer Laravel UI et API en parallèle**  

Laravel UI fonctionne **avec les sessions** (`auth:web`), tandis que l’API fonctionne avec **les tokens** (`auth:api`).  

💡 **Astuces :**
- Laravel UI ne sera pas affecté.
- L'API et Laravel UI utilisent le **même modèle `User`**, donc un utilisateur peut se connecter via **l'interface web** et via **l'API séparément**.

---

## **Résumé**
✔ **Laravel UI** continue à fonctionner normalement.  
✔ **API login/logout** fonctionne séparément sans affecter l’authentification web.  
✔ **Sécurité assurée** via `auth:api` (Passport).  

🔹 Maintenant, tu peux gérer ton authentification API sans conflit avec Laravel UI ! 🚀