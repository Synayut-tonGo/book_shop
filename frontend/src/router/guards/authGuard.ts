import { useAuthStore } from "@/stores/auth";
import { getToken } from "@/utils/cookies";
import { isAdminOrSuperAdmin } from "@/utils/roleUtils";
import type { NavigationGuardNext, RouteLocationNormalized } from "vue-router";


export default async (to : RouteLocationNormalized , from: RouteLocationNormalized , next : NavigationGuardNext) => {

 const authStore = useAuthStore();

    if (getToken() && !authStore.user) {
        await authStore.fetchUser();
    }

    const isLoggedIn = !!getToken();
    const isAdmin = isAdminOrSuperAdmin(authStore.user?.roles);

    const isAdminArea = to.matched.some(r => r.meta.requiresAdmin);
    const isGuestOnly = to.matched.some(r => r.meta.guestOnly);

    // Guest pages
    if (isGuestOnly) {
        if (isLoggedIn) {
            return next(isAdmin ? '/admin' : '/');
        }
        return next();
    }

    // Not logged in
    if (!isLoggedIn) {
        return next({ name: 'login' });
    }

    // ❌ User trying to access admin
    if (isAdminArea && !isAdmin) {
        return next('/');
    }

    // ❌ Admin trying to access customer (THIS IS YOUR FIX)
    if (!isAdminArea && isAdmin) {
        return next('/admin');
    }

    next();

}