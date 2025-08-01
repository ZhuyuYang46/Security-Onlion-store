# Database Security Testing Setup

## 🚀 Quick Start

### 1. Run Database Migrations & Seeders
```bash
# Run migrations to create tables
php artisan migrate

# Seed the database with test data
php artisan db:seed
```

### 2. Test Login Credentials

| User | Email | Password | Orders | Purpose |
|------|-------|----------|---------|---------|
| Alice Johnson | `alice@example.com` | `password123` | 3 orders | Test multi-order user |
| Bob Smith | `bob@example.com` | `password123` | 2 orders | Test moderate orders |
| Carol Davis | `carol@example.com` | `password123` | 1 order | Test single order |
| Dave Wilson | `dave@example.com` | `password123` | 0 orders | Test empty state |

## 🔐 Security Features to Test

### Test Row-Level Security:
1. Login as `alice@example.com`
2. Go to `/my-orders` - see Alice's 3 orders
3. Note the order IDs (e.g., 1, 2, 3)
4. Logout and login as `bob@example.com`
5. Go to `/my-orders` - see only Bob's orders
6. Try manually visiting `/order/1` (Alice's order) - **Access Denied!**

### Test Data Encryption:
- All sensitive data (addresses, phone numbers, credit cards) are encrypted in database
- Data is decrypted only for authorized viewing
- Each order shows 🔓 icons indicating decrypted sensitive data

### Test Empty State:
- Login as `dave@example.com`
- Go to `/my-orders` - see "No Orders Found" message

### Test Audit Trail:
- All login attempts, order views, and unauthorized access attempts are logged
- Check `audit_logs` table in database to see complete activity trail

## 📦 Sample Order Data

**Alice's Orders:**
- MacBook Pro 16" (San Francisco address)
- iPhone 15 Pro x2 (same address) 
- AirPods Pro (Palo Alto address)

**Bob's Orders:**
- Gaming Desktop PC (Austin address)
- Mechanical Keyboard (same address)

**Carol's Order:**
- Smart Watch (Denver address)

All credit card and personal data is encrypted with AES-256 before database storage.