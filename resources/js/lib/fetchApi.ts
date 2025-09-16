import { getCookie } from '@/lib/cookie/getCookie';

export async function fetchApi<T = unknown>(
    input: RequestInfo,
    init?: RequestInit,
): Promise<T> {
    await fetch('/sanctum/csrf-cookie');

    const token = decodeURIComponent(getCookie('XSRF-TOKEN'));

    const response = await fetch(input, {
        credentials: 'include',
        ...init,
        headers: {
            Accept: 'application/json, text/plain, */*',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-XSRF-TOKEN': token,
            ...init?.headers,
        },
    });

    if (!response.ok) {
        const error = new Error(`HTTP error! status: ${response.status}`);
        (error as any).response = response;
        throw error;
    }

    return response.json();
}
