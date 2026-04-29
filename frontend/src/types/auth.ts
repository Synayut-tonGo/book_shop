export interface Role {
    role_id: number;
    name: string;
    slug: string;
    created_by: number;
}

export interface User {
    user_id: number;
    first_name: string;
    last_name: string;
    full_name: string;
    phone: string;
    email: string;
    password?: string;
    dob: Date | string;
    age: number;
    gender: string;
    image: string | undefined;
    roles: Role[];
    created_at?: string;
    updated_at?: string;
}

export interface RegisterUser {
    first_name: string;
    last_name: string;
    phone: string;
    email: string;
    password: string;
    password_confirmation: string;
    dob: Date;
    gender: 'male' | 'female';
    image: string | null;
}

export interface CreateUser {
    first_name: string;
    last_name: string;
    phone: string;
    email: string;
    password?: string;
    dob: Date;
    gender: string;
    image: File | null;
}