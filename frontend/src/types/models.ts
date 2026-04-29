export interface Book {
    book_id: number;
    code: string;
    name: string;
    description: string;
    quantity: number;
    discount: number;
    status: string;
    image?: string;
    created_at?: string;
    updated_at?: string;
}

export interface CreateBook {
    name: string;
    description: string;
    quantity: number;
    discount: number;
    code: string;
    status: string;
    image: File | null;
}

export interface Author {
    author_id: number;
    code: string;
    name: string;
    bio: string;
    image?: string;
    status?: string;
    created_at?: string;
    updated_at?: string;
}

export interface CreateAuthor {
    name: string;
    bio: string;
    image: File | null;
}

export interface Category {
    category_id: number;
    name: string;
    code: string;
    status: string;
    created_at?: string;
    updated_at?: string;
}

export interface CreateCategory {
    name: string;
    code: string;
    status: string;
}