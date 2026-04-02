# Sistema de Alertas de Pharmacovigilance

Sistema para identificar y notificar clientes que adquirieron medicamentos con un numero de lote especifico (filtrar por este numero tiene mas datos 951357) dentro de un rango de fechas definido.

- url:
http://127.0.0.1:8000/

## Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js 22 o superior
- NPM
- MySQL 8.0 o superior

## Tecnologias Utilizadas

- Laravel 12
- Vue.js 3
- Vue Router 4
- Tailwind CSS 4
- MySQL 
- Laravel Sanctum (autenticacion con tokens)
- maatwebsite/excel (exportacion Excel)
- barryvdh/laravel-dompdf (exportacion PDF)
- SweetAlert2 (alertas)

## Instalacion

### 1. Clonar el repositorio

```bash
git clone https://github.com/crixus12cr/prueba-LifeFile.git
cd prueba-LifeFile
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
```

Editar el archivo .env:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lifefile
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
QUEUE_CONNECTION=database
```

### 4. Generar clave de aplicacion

```bash
php artisan key:generate
```
### 5. Instalar dependencias Node.js

```bash
npm install
```

### 6. Ejecutar migraciones y seeders

```bash
php artisan migrate
php artisan db:seed
```

### 7. Configurar cola para jobs

```bash
php artisan queue:table
php artisan migrate
```

# Ejecucion del Proyecto

Terminal 1 - Servidor Laravel:

```bash
php artisan serve
```

Terminal 2 - Compilacion Vue:

```bash
npm run dev
```

Terminal 3 - Worker de colas (para emails):

```bash
php artisan queue:work
```

# Credenciales de Prueba
| Campo    | Valor             |
|----------|------------------|
| Email    | admin@pharma.com |
| Password | 12345678      |

# Estructura del Backend
Se implementa el Patron Repository con separacion clara de responsabilidades:
```bash
app/Http/
├── Controllers/Api/         # Manejo de peticiones HTTP
├── Requests/                # Validacion de datos
├── Services/                # Logica de negocio
├── Repositories/            # Consultas a base de datos
└── Middleware/              # Middleware personalizado

app/
├── Models/                  # Modelos Eloquent
├── Exports/                 # Exportadores Excel
├── Jobs/                    # Jobs para colas
└── Mail/                    # Mailables
```

## Flujo de datos

Request -> Controller -> Service -> Repository -> Base de datos
            |
            v
Controller (try-catch) -> JSON Response

## Endpoints API

| Metodo | Endpoint                                      | Descripcion           |
|--------|-----------------------------------------------|-----------------------|
| POST   | /api/login                                    | Iniciar sesion        |
| POST   | /api/logout                                   | Cerrar sesion         |
| GET    | /api/medications/search?lot=xxx               | Buscar medicamento    |
| GET    | /api/orders?lot=xxx&page=1&per_page=15        | Listar ordenes        |
| GET    | /api/orders/{id}                             | Detalle de orden      |
| GET    | /api/customers/{id}                          | Detalle de cliente    |
| POST   | /api/alerts/send                             | Enviar alerta         |
| GET    | /api/orders/export/excel?lot=xxx             | Exportar a Excel      |
| GET    | /api/orders/export/pdf?lot=xxx               | Exportar a PDF        |



# Documentación del Proyecto
````markdown id="78214"

---

##  Rutas del Frontend

| Ruta            | Componente      |
|-----------------|-----------------|
| /               | Login           |
| /dashboard      | Dashboard       |
| /orders/:id     | OrderDetail     |
| /customers/:id  | CustomerDetail  |

---

## Patrón Repository

### Controller
Solo maneja peticiones HTTP y tiene try-catch:

```php
public function index(ListOrdersRequest $request): JsonResponse {
    try {
        return $this->orderService->getOrdersByLotNumber($request);
    } catch(\Exception $e) {
        return response()->json([
            'message' => 'An error occurred',
            'errors' => [$e->getMessage()],
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}
````

---

### Service

Contiene lógica de negocio y retorna JsonResponse:

```php
public function getOrdersByLotNumber(ListOrdersRequest $request): JsonResponse {
    $medication = $this->medicationRepository->findByLotNumber($request->lot_number);
    
    if(!$medication) {
        return response()->json([
            'message' => 'Medication not found',
            'errors' => ['No medication found'],
        ], JsonResponse::HTTP_NOT_FOUND);
    }
    
    return response()->json([
        'data' => $orders,
        'message' => 'Orders retrieved successfully',
    ], JsonResponse::HTTP_OK);
}
```

---

### Repository

Solo realiza consultas a base de datos:

```php
public function findByLotNumber(string $lotNumber): ?Medication {
    return Medication::where('lot_number', $lotNumber)->first();
}
```

---

### Request

Valida datos y verifica autenticación:

```php
public function authorize(): bool {
    return auth('sanctum')->check();
}

public function rules(): array {
    return [
        'lot_number' => 'required|string|max:50',
        'start_date' => 'nullable|date_format:Y-m-d',
        'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
    ];
}
```

---

## Autenticación con Sanctum

### Middleware personalizado

```php
public function handle(Request $request, Closure $next): Response {
    if (!$request->user('sanctum')) {
        return response()->json([
            'message' => 'Unauthenticated',
            'errors' => ['Authentication required'],
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
    return $next($request);
}
```

### Rutas protegidas

```php
Route::middleware('api.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/orders', [OrderController::class, 'index']);
});
```

---

## Colas y Jobs para Emails

### Crear Job

```bash
php artisan make:job SendAlertEmailJob
```

### Dispatch del Job

```php
SendAlertEmailJob::dispatch($customer, $order, $medicationName, $lotNumber);
```

### Ejecutar worker

```bash
php artisan queue:work
```

---

## Paginación

La API soporta paginación:

| Parametro | Default | Descripcion           |
| --------- | ------- | --------------------- |
| page      | 1       | Numero de pagina      |
| per_page  | 15      | Resultados por pagina |

### Respuesta paginada

```json
{
    "data": [...],
    "meta": {
        "current_page": 1,
        "last_page": 6,
        "per_page": 15,
        "total": 80
    },
    "links": {
        "next": "http://localhost/api/orders?page=2",
        "prev": null
    }
}
```

---

## Datos de Prueba

### Ejecutar seeders

```bash
php artisan migrate:fresh --seed
```

### Datos generados

* 50 clientes
* 30 medicamentos con lote 951357
* 300-600 ordenes

---

## Decisiones de Diseño

### Backend

* Patrón Repository para separar lógica de negocio y acceso a datos
* Services centralizan lógica reutilizable
* Requests para validación y transformación
* Middleware personalizado sin redirecciones
* Tipado estricto en todas las funciones

### Frontend

* Vue 3 con Composition API
* Vue Router con guards de autenticación
* Tailwind CSS para estilos
* SweetAlert2 para alertas
* Axios interceptors para manejo de tokens

---

## Suposiciones

* En desarrollo se usa `MAIL_MAILER=smtp` con mailtrap de prueba para envio de correos.
* Sistema busca un lote a la vez
* Rango de fechas por defecto: últimos 30 días
* Paginación: 15 resultados por página
* Exportaciones incluyen todas las órdenes sin paginación

---

## Comandos Útiles

### Laravel

```bash
php artisan route:list                                      # Listar rutas
php artisan make:model Nombre -m                            # Crear modelo con migracion
php artisan make:request Nombre                             # Crear request
php artisan make:controller Api/NombreController            # Crear controlador
php artisan make:class Http/Services/NombreService          # Crear servicio
php artisan make:class Http/Repositorries/NombreRepository  # Crear repositorio
```

### Vue

```bash
npm run dev     # Compilar assets (desarrollo)
npm run build   # Compilar assets (produccion)
```

---

## Solución de Problemas

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Contacto

Desarrollado por Cristian Stiven Perdomo Garcia 
- phone:   +57 3125620823
- email: cristian2020til@gmail.com



