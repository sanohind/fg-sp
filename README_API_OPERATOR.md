# Operator API - Mobile App

API untuk mobile app operator yang memungkinkan operasi store, pull, dan manajemen inventory secara real-time.

## Fitur Utama

- **Authentication & Authorization**: Login dengan role-based access control
- **Store Operations**: Operasi penyimpanan item ke slot
- **Pull Operations**: Operasi pengambilan item dari slot
- **Real-time Validation**: Validasi slot, quantity, dan ERP code
- **Activity Tracking**: Log semua aktivitas operator
- **Search & Utilities**: Pencarian item dan informasi slot

## Prerequisites

- PHP 8.1+
- Laravel 11+
- MySQL/PostgreSQL
- Laravel Sanctum (untuk API authentication)

## Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd fg-sp
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
```bash
# Update .env file dengan database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fg_sp
DB_USERNAME=root
DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed database dengan data awal
php artisan db:seed
```

### 5. Storage Setup
```bash
php artisan storage:link
```

### 6. Start Development Server
```bash
php artisan serve
```

## API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Authentication Flow

1. **Login** → `POST /api/auth/login`
2. **Use Token** → Include `Authorization: Bearer {token}` header
3. **Refresh Token** → `POST /api/auth/refresh` (optional)
4. **Logout** → `POST /api/auth/logout`

### Quick Start Example

#### 1. Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "username": "operator123",
    "password": "password123"
  }'
```

#### 2. Use Token
```bash
curl -X GET http://localhost:8000/api/operator/dashboard \
  -H "Authorization: Bearer {your_token}" \
  -H "Accept: application/json"
```

## Testing

### Postman Collection
Import file `Operator_API_Postman_Collection.json` ke Postman untuk testing lengkap.

### Environment Variables
Set environment variables di Postman:
- `base_url`: `http://localhost:8000`
- `auth_token`: Token dari login response

### Testing Flow
1. Import collection
2. Set environment variables
3. Run "Login" request
4. Copy token dari response ke `auth_token` variable
5. Test endpoint lainnya

## Mobile App Integration

### Flutter Example
```dart
class OperatorApiService {
  static const String baseUrl = 'http://your-domain.com/api';
  static String? authToken;

  static Future<Map<String, dynamic>> login(String username, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'username': username,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      authToken = data['data']['token'];
      return data;
    } else {
      throw Exception('Login failed');
    }
  }

  static Future<Map<String, dynamic>> getDashboard() async {
    final response = await http.get(
      Uri.parse('$baseUrl/operator/dashboard'),
      headers: {
        'Authorization': 'Bearer $authToken',
        'Accept': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to get dashboard');
    }
  }
}
```

### React Native Example
```javascript
class OperatorApiService {
  static baseUrl = 'http://your-domain.com/api';
  static authToken = null;

  static async login(username, password) {
    try {
      const response = await fetch(`${this.baseUrl}/auth/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          username,
          password,
        }),
      });

      const data = await response.json();
      if (data.success) {
        this.authToken = data.data.token;
        return data;
      } else {
        throw new Error(data.message);
      }
    } catch (error) {
      throw error;
    }
  }

  static async getDashboard() {
    try {
      const response = await fetch(`${this.baseUrl}/operator/dashboard`, {
        headers: {
          'Authorization': `Bearer ${this.authToken}`,
          'Accept': 'application/json',
        },
      });

      const data = await response.json();
      if (data.success) {
        return data;
      } else {
        throw new Error(data.message);
      }
    } catch (error) {
      throw error;
    }
  }
}
```

## Error Handling

### Common HTTP Status Codes
- `200` - Success
- `400` - Bad Request (validation error)
- `401` - Unauthorized (invalid token)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

### Error Response Format
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field": ["Error message"]
  }
}
```

## Security Features

### Authentication
- Laravel Sanctum tokens
- Token expiration (configurable)
- Automatic token refresh

### Authorization
- Role-based access control
- Middleware protection
- API rate limiting

### Data Validation
- Input sanitization
- SQL injection prevention
- XSS protection

## Performance Optimization

### Database
- Eager loading relationships
- Database indexing
- Query optimization

### Caching
- Response caching
- Database query caching
- Static asset caching

### Rate Limiting
- 60 requests/minute (authenticated)
- 30 requests/minute (unauthenticated)

## Monitoring & Logging

### Activity Logs
- User actions tracking
- Database changes logging
- Error logging

### Performance Metrics
- Response time monitoring
- Database query analysis
- API usage statistics

## Deployment

### Production Environment
```bash
# Set environment
APP_ENV=production
APP_DEBUG=false

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Queue workers
php artisan queue:work
```

### Docker Deployment
```dockerfile
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www
```

## Troubleshooting

### Common Issues

#### 1. Token Expired
```bash
# Refresh token
POST /api/auth/refresh
```

#### 2. CORS Issues
```php
// config/cors.php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

#### 3. Database Connection
```bash
# Check database connection
php artisan tinker
DB::connection()->getPdo();
```

### Debug Mode
```bash
# Enable debug mode
APP_DEBUG=true

# View logs
tail -f storage/logs/laravel.log
```

## Support

### Documentation
- [API Documentation](API_DOCUMENTATION.md)
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)

### Issues
- Create GitHub issue
- Check existing issues
- Provide error logs and steps to reproduce

## Contributing

1. Fork repository
2. Create feature branch
3. Make changes
4. Add tests
5. Submit pull request

## License

This project is licensed under the MIT License.
