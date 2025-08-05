# Security Assignment: Enterprise Application Security (EAS) Demonstration

## 📋 Assignment Overview

This project demonstrates comprehensive Enterprise Application Security (EAS) principles through a Laravel-based e-commerce application that showcases both secure and vulnerable implementations of critical security controls.

## 🎯 Learning Objectives

### Enterprise Application Security (EAS) Focus Areas

1. **Input Validation & Sanitization**
   - SQL Injection prevention and demonstration
   - Cross-Site Scripting (XSS) mitigation techniques
   - Parameter validation and filtering

2. **Authentication & Authorization**
   - Secure password handling and hashing
   - Session management best practices
   - Row-level security implementation

3. **Data Protection**
   - Encryption at rest (AES-256)
   - Sensitive data handling
   - Database security patterns

4. **Security Monitoring & Auditing**
   - Comprehensive audit logging
   - Access attempt tracking
   - Security event monitoring

## 🏗️ Project Architecture

### Core Components

- **Framework**: Laravel 10.x (PHP)
- **Database**: MySQL with encrypted sensitive data storage
- **Security Features**: 
  - CSRF protection
  - Password hashing (bcrypt)
  - Data encryption (AES-256)
  - Audit trails

### Security Demonstration Modules

#### 1. SQL Injection Demo
- **Vulnerable Route**: `/login-vuln` - Raw SQL queries susceptible to injection
- **Secure Route**: `/login-safe` - Parameterized queries with prepared statements
- **Attack Vectors**: Authentication bypass, data extraction

#### 2. Cross-Site Scripting (XSS) Demo
- **Vulnerable Route**: `/comment-vuln` - Unescaped user input rendering
- **Secure Route**: `/comment-safe` - Proper output encoding and sanitization
- **Attack Types**: Stored XSS, reflected XSS prevention

#### 3. Data Protection & Encryption
- **Secure Implementation**: `/order`, `/my-orders` - Encrypted sensitive data
- **Vulnerable Implementation**: `/order-vuln`, `/my-orders-vuln` - Plain text storage
- **Protection Scope**: Credit cards, addresses, phone numbers

## 🔐 Security Controls Implemented

### 1. Input Validation
```php
// Secure parameterized queries
$user = DB::select('SELECT * FROM users WHERE email = ? AND password = ?', [$email, $hashedPassword]);

// Vulnerable raw queries (demonstration only)
$user = DB::select("SELECT * FROM users WHERE email = '$email' AND password = '$password'");
```

### 2. Output Encoding
```blade
{{-- Secure output (auto-escaped) --}}
{{ $comment->content }}

{{-- Vulnerable output (unescaped) --}}
{!! $comment->content !!}
```

### 3. Data Encryption
```php
// Sensitive data encryption before storage
'credit_card' => encrypt($request->credit_card),
'shipping_address' => encrypt($request->shipping_address),
'phone' => encrypt($request->phone)
```

### 4. Access Control
```php
// Row-level security enforcement
$order = Order::where('id', $id)
              ->where('user_id', Auth::id())
              ->firstOrFail();
```

## 📊 Test Scenarios

### User Test Accounts
| User | Email | Password | Orders | Security Test Purpose |
|------|-------|----------|---------|---------------------|
| Alice Johnson | `alice@example.com` | `password123` | 3 orders | Multi-order authorization testing |
| Bob Smith | `bob@example.com` | `password123` | 2 orders | Cross-user access prevention |
| Carol Davis | `carol@example.com` | `password123` | 1 order | Single order security validation |
| Dave Wilson | `dave@example.com` | `password123` | 0 orders | Empty state security handling |

### Security Test Cases

#### SQL Injection Testing
1. **Attack Payload**: `' OR '1'='1' --`
2. **Vulnerable Endpoint**: POST `/login-vuln`
3. **Expected Result**: Authentication bypass in vulnerable version
4. **Secure Behavior**: Login failure with proper error handling

#### XSS Testing
1. **Attack Payload**: `<script>alert('XSS')</script>`
2. **Vulnerable Endpoint**: POST `/comment-vuln`
3. **Expected Result**: Script execution in vulnerable version
4. **Secure Behavior**: Safe HTML rendering with escaped content

#### Authorization Testing
1. **Test Method**: Cross-user order access attempts
2. **Attack Vector**: Direct URL manipulation (`/order/{other_user_order_id}`)
3. **Expected Secure Result**: Access denied with audit log entry

## 🛡️ Enterprise Security Best Practices Demonstrated

### 1. Defense in Depth
- Multiple security layers (input validation, output encoding, access control)
- Fail-safe defaults and least privilege principles
- Comprehensive error handling without information disclosure

### 2. Secure Development Lifecycle
- Security-by-design implementation patterns
- Vulnerability remediation examples
- Code review checkpoints for security controls

### 3. Compliance & Auditing
- Complete audit trail for all security-relevant actions
- Tamper-evident logging with timestamps
- Access pattern monitoring and anomaly detection

### 4. Data Classification & Protection
- Sensitive data identification and classification
- Appropriate encryption for data at rest
- Secure key management practices

## 📈 Security Metrics & Monitoring

### Audit Log Coverage
- Authentication attempts (success/failure)
- Authorization violations
- Data access patterns
- Administrative actions

### Security Event Types
```sql
-- Sample audit log structure
CREATE TABLE audit_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NULL,
    action VARCHAR(100) NOT NULL,
    resource_type VARCHAR(50),
    resource_id BIGINT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🎓 Educational Value

### Enterprise Application Security Concepts
1. **Threat Modeling**: Understanding attack vectors and mitigation strategies
2. **Secure Coding**: Implementation of security controls in real applications
3. **Risk Assessment**: Evaluation of vulnerability impact and likelihood
4. **Incident Response**: Detection and response to security events

### Industry Standards Alignment
- **OWASP Top 10**: Direct mapping to common web application risks
- **NIST Cybersecurity Framework**: Implementation of protective controls
- **ISO 27001**: Information security management principles

## 🚀 Setup & Testing Instructions

### Quick Start
```bash
# Install dependencies
composer install

# Database setup
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
```

### Security Testing Workflow
1. **Baseline Testing**: Test secure implementations first
2. **Vulnerability Demonstration**: Compare with vulnerable versions
3. **Mitigation Verification**: Confirm security controls effectiveness
4. **Audit Review**: Examine generated security logs

## 📚 References & Further Reading

- [OWASP Web Security Testing Guide](https://owasp.org/www-project-web-security-testing-guide/)
- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [NIST Application Security Guidelines](https://csrc.nist.gov/publications/detail/sp/800-53/rev-5/final)
- [Enterprise Application Security Architecture](https://www.sans.org/white-papers/enterprise-application-security/)

---

## 🏆 Assignment Deliverables

This project serves as a comprehensive demonstration of Enterprise Application Security principles, providing hands-on experience with:
- Vulnerability identification and exploitation
- Security control implementation and validation
- Security monitoring and incident detection
- Secure software development practices

The comparative approach (secure vs. vulnerable implementations) enables deep understanding of both attack methodologies and defensive strategies essential for enterprise security professionals.