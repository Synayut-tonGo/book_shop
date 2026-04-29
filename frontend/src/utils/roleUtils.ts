import type { Role } from "@/types/auth";
import { ROLES } from "./constants";

export const  hasRole = (roles : Role[] | undefined , slug: string):boolean => {
    return roles?.some(r => r.slug === slug) ?? false;
}

export const isAdminOrSuperAdmin = (roles:Role[] | undefined): boolean => {
    return hasRole(roles, ROLES.ADMIN) || hasRole(roles, ROLES.SUPER_ADMIN)
}   

export const getDashboardRoute = (roles : Role[] | undefined):string => {
    return isAdminOrSuperAdmin(roles) ? '/admin' : '/'
}