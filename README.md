# 📦 Product Management System (Laravel)

A simple Product Management System built with **Laravel** that allows users to **Add, Edit, View, and Delete products** with **multiple image uploads**, using **AJAX** and **Yajra DataTables** (no page refresh).

---

## 🚀 Features

- Product CRUD (Create, Read, Update, Delete)
- Multiple image upload per product
- AJAX-based operations (no page reload)
- Yajra DataTables integration
- Image preview and dynamic update
- Delete product along with images
- CSRF protection
- Clean MVC structure

---

## 🛠 Tech Stack

- **Backend:** Laravel 9  
- **Frontend:** Blade, jQuery, AJAX, Bootstrap  
- **Database:** MySQL  
- **Datatables:** Yajra Laravel DataTables  
- **Storage:** Laravel Public Storage  

---

## 📂 Database Structure

### products table
- id
- product_name
- product_price
- product_desc
- created_at
- updated_at

### products_img table
- id
- product_id (foreign key)
- image_path
- created_at
- updated_at

---

## 🔑 Installation Steps

### 1️⃣ Clone Repository
```bash
git clone https://github.com/sutarseema32-arch/product-management-sysyem.git
cd product-management-sysyem


## Project GitHub repository:  
https://github.com/sutarseema32-arch/product-management-sysyem.git