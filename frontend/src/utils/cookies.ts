// utils/cookies.ts
import Cookies from "js-cookie";
import { TOKEN } from "./constants";

export const getToken = () => Cookies.get(TOKEN);

export const setToken = (token: string) => Cookies.set(TOKEN, token, { expires: 7 }); // 7 = days ✅

export const removeToken = () => Cookies.remove(TOKEN);