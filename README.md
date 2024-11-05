## Layihə Haqqında

Order Management API, müştəri sifarişlərini idarə etmək üçün yaradılmış bir RESTful API-dir. 
Bu API, müştəri qeydiyyatı, giriş, sifariş yaratma, yeniləmə, görüntüləmə və silmə əməliyyatlarını dəstəkləyir. 
JWT (JSON Web Token) ilə autentifikasiya istifadə olunur.

## Quraşdırma

1. **Layihəni Klonlayın:**
   git clone https://github.com/RamilHuseynov/order-management.git

2. **Dəyişiklikləri quraşdırmaq üçün:**
   - composer install
   - .env faylını yaradın ve verilənlər bazası tənzimləmələrini düzəldin
     
3. **JWT Konfiqurasiyası:**
   - php artisan jwt:secret

4. **Serveri Başladın:**
   - php artisan serve

## Texnologiyalar
- **PHP**
- **Laravel**
- **JWT**
- **MySQL**

## İstifadəçi Qayda və Tələbləri
Bu API-nin istifadə edilməsi üçün aşağıdakı şərtlərə əməl edilməlidir:
- **İstifadəçilər qeydiyyatdan keçməli və giriş etməlidirlər.**
- **Yalnız autentifikasiya olunmuş istifadəçilər sifariş yaratmaq, yeniləmək və silmək hüququna malikdir.**
