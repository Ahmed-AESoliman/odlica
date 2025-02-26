# E-Commerce Database Schema

This repository contains the **Entity-Relationship Diagram (ERD)** for an e-commerce platform, designed using **Mermaid.js**.

## ER Diagram

```mermaid
erDiagram
    CATEGORIES {
        id int PK
        name string
        slug string
        description text
        image string
        parent_id int FK
        position int
        active boolean
        created_at timestamp
        updated_at timestamp
    }

    PRODUCTS {
        id int PK
        name string
        slug string
        description text
        price decimal
        sale_price decimal
        stock int
        sku string
        image string
        active boolean
        featured boolean
        category_id int FK
        brand_id int FK
        specifications json
        average_rating decimal
        on_sale boolean
        discount_percentage decimal
        new_until timestamp
        created_at timestamp
        updated_at timestamp
    }

    BRANDS {
        id int PK
        name string
        slug string
        logo string
        featured boolean
        created_at timestamp
        updated_at timestamp
    }

    COLORS {
        id int PK
        name string
        value string
        created_at timestamp
        updated_at timestamp
    }

    SIZES {
        id int PK
        name string
        value string
        type string
        created_at timestamp
        updated_at timestamp
    }

    PRODUCT_VARIANTS {
        id int PK
        product_id int FK
        sku string
        price decimal
        sale_price decimal
        stock int
        image string
        created_at timestamp
        updated_at timestamp
    }

    PRODUCT_IMAGES {
        id int PK
        product_id int FK
        image string
        position int
        created_at timestamp
        updated_at timestamp
    }

    PRODUCT_COLOR {
        id int PK
        product_id int FK
        color_id int FK
        created_at timestamp
        updated_at timestamp
    }

    PRODUCT_SIZE {
        id int PK
        product_id int FK
        size_id int FK
        created_at timestamp
        updated_at timestamp
    }

    VARIANT_COLOR {
        id int PK
        product_variant_id int FK
        color_id int FK
        created_at timestamp
        updated_at timestamp
    }

    VARIANT_SIZE {
        id int PK
        product_variant_id int FK
        size_id int FK
        created_at timestamp
        updated_at timestamp
    }

    CART_ITEMS {
        id int PK
        session_id string
        product_id int FK
        product_variant_id int
        quantity int
        created_at timestamp
        updated_at timestamp
    }

    CATEGORIES ||--o{ CATEGORIES : "parent_child"
    CATEGORIES ||--o{ PRODUCTS : "has"
    BRANDS ||--o{ PRODUCTS : "has"
    PRODUCTS ||--o{ PRODUCT_VARIANTS : "has"
    PRODUCTS ||--o{ PRODUCT_IMAGES : "has"
    PRODUCTS ||--o{ PRODUCT_COLOR : "has"
    PRODUCTS ||--o{ PRODUCT_SIZE : "has"
    COLORS ||--o{ PRODUCT_COLOR : "used_in"
    SIZES ||--o{ PRODUCT_SIZE : "used_in"
    PRODUCT_VARIANTS ||--o{ VARIANT_COLOR : "has"
    PRODUCT_VARIANTS ||--o{ VARIANT_SIZE : "has"
    COLORS ||--o{ VARIANT_COLOR : "used_in"
    SIZES ||--o{ VARIANT_SIZE : "used_in"
    PRODUCTS ||--o{ CART_ITEMS : "added_to"
    PRODUCT_VARIANTS ||--o{ CART_ITEMS : "added_to"
```
