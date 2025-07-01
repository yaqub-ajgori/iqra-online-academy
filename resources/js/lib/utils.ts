import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';
import { cva } from 'class-variance-authority';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

/**
 * Format date consistently across the application
 */
export function formatDate(date: string | Date | null | undefined): string {
    if (!date) return 'N/A';

    const dateObj = typeof date === 'string' ? new Date(date) : date;

    // Check if date is valid
    if (isNaN(dateObj.getTime())) return 'Invalid Date';

    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
    }).format(dateObj);
}

/**
 * Format datetime consistently across the application
 */
export function formatDateTime(datetime: string | Date | null | undefined): string {
    if (!datetime) return '';

    const dateObj = typeof datetime === 'string' ? new Date(datetime) : datetime;

    // Check if date is valid
    if (isNaN(dateObj.getTime())) return '';

    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    }).format(dateObj);
}

/**
 * Get user initials from full name
 */
export function getInitials(fullName?: string): string {
    if (!fullName) return 'U';

    const names = fullName.trim().split(' ');

    if (names.length === 0) return 'U';
    if (names.length === 1) return names[0].charAt(0).toUpperCase();

    return `${names[0].charAt(0)}${names[names.length - 1].charAt(0)}`.toUpperCase();
}

/**
 * Format number with commas
 */
export function formatNumber(number: number | string): string {
    const num = typeof number === 'string' ? parseFloat(number) : number;
    if (isNaN(num)) return '0';

    return new Intl.NumberFormat('en-US').format(num);
}

/**
 * Format currency in Bangladeshi Taka
 */
export function formatCurrency(amount: number | string, currencyCode: string = 'USD'): string {
    const num = typeof amount === 'string' ? parseFloat(amount) : amount;

    const options: Intl.NumberFormatOptions = {
        style: 'currency',
        currency: currencyCode,
    };

    if (isNaN(num)) {
        try {
            return new Intl.NumberFormat('en-US', options).format(0);
        } catch (e) {
            return new Intl.NumberFormat('en-US', { ...options, currency: 'USD' }).format(0);
        }
    }

    try {
        return new Intl.NumberFormat('en-US', options).format(num);
    } catch (e) {
        // Fallback to USD if currency code is invalid
        return new Intl.NumberFormat('en-US', { ...options, currency: 'USD' }).format(num);
    }
}

/**
 * Truncate text to specified length
 */
export function truncateText(text: string, maxLength: number = 50): string {
    if (!text) return '';
    if (text.length <= maxLength) return text;

    return text.substring(0, maxLength) + '...';
}

/**
 * Get status color classes
 */
export function getStatusColor(status: string): string {
    const statusColors: Record<string, string> = {
        active: 'bg-green-100 text-green-800',
        inactive: 'bg-red-100 text-red-800',
        pending: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-blue-100 text-blue-800',
        verified: 'bg-emerald-100 text-emerald-800',
        published: 'bg-green-100 text-green-800',
        draft: 'bg-gray-100 text-gray-800',
        completed: 'bg-blue-100 text-blue-800',
        cancelled: 'bg-red-100 text-red-800',
        processing: 'bg-orange-100 text-orange-800',
    };

    return statusColors[status.toLowerCase()] || 'bg-gray-100 text-gray-800';
}

/**
 * Get payment status color classes
 */
export function getPaymentStatusClass(status: string): string {
    const statusClasses: Record<string, string> = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        failed: 'bg-red-100 text-red-800',
        cancelled: 'bg-gray-100 text-gray-800',
        refunded: 'bg-purple-100 text-purple-800',
    };
    return statusClasses[status.toLowerCase()] || 'bg-gray-100 text-gray-800';
}

/**
 * Capitalize first letter of each word
 */
export function capitalizeWords(text: string): string {
    if (!text) return '';

    return text.replace(/\w\S*/g, (txt) =>
        txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
    );
}

/**
 * Generate a random ID
 */
export function generateId(): string {
    return `id_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
}

/**
 * Sleep utility for async operations
 */
export function sleep(ms: number): Promise<void> {
    return new Promise(resolve => setTimeout(resolve, ms));
}

/**
 * Debounce function for search inputs
 */
export function debounce<T extends (...args: any[]) => any>(
    func: T,
    wait: number
): (...args: Parameters<T>) => void {
    let timeout: NodeJS.Timeout | null = null;

    return (...args: Parameters<T>) => {
        if (timeout) clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
}

// Button variants for consistent styling across the app
export const buttonVariants = cva(
    'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
    {
        variants: {
            variant: {
                default: 'bg-primary text-primary-foreground shadow hover:bg-primary/90 active:scale-[0.98]',
                primary: 'bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white shadow-lg shadow-[#5f5fcd]/25 hover:shadow-xl hover:shadow-[#5f5fcd]/30 active:scale-[0.98] font-semibold',
                secondary: 'bg-secondary text-secondary-foreground shadow-sm hover:bg-secondary/80',
                destructive: 'bg-destructive text-destructive-foreground shadow-sm hover:bg-destructive/90',
                outline: 'border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground',
                ghost: 'hover:bg-accent hover:text-accent-foreground',
                link: 'text-primary underline-offset-4 hover:underline',
                success: 'bg-green-600 text-white shadow hover:bg-green-700 active:scale-[0.98]',
                warning: 'bg-orange-600 text-white shadow hover:bg-orange-700 active:scale-[0.98]',
                info: 'bg-blue-600 text-white shadow hover:bg-blue-700 active:scale-[0.98]',
            },
            size: {
                xs: 'h-7 rounded-md px-2 text-xs gap-1',
                sm: 'h-8 rounded-md px-3 text-xs gap-1.5',
                default: 'h-9 px-4 py-2 gap-2',
                lg: 'h-10 rounded-md px-8 gap-2',
                xl: 'h-11 rounded-md px-8 gap-2',
                icon: 'h-9 w-9 gap-0',
                'icon-sm': 'h-8 w-8 gap-0',
                'icon-xs': 'h-7 w-7 gap-0',
            },
        },
        defaultVariants: {
            variant: 'default',
            size: 'default',
        },
    }
)
