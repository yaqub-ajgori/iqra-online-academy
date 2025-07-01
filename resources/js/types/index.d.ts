import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

declare global {
    namespace App {
        namespace Data {
            interface User {
                id: number;
                name: string;
                email: string;
                avatar?: string;
                email_verified_at?: string;
                created_at: string;
                updated_at: string;
            }

            interface Student {
                id: number;
                user_id: number;
                full_name: string;
                phone?: string;
                date_of_birth?: string;
                gender?: 'male' | 'female' | 'other';
                address?: string;
                city?: string;
                district?: string;
                country?: string;
                postal_code?: string;
                profile_picture?: string;
                bio?: string;
                occupation?: string;
                education_level?: string;
                is_active: boolean;
                is_verified: boolean;
                created_at: string;
                updated_at: string;
            }

            interface CourseCategory {
                id: number;
                name: string;
                slug: string;
                description?: string;
                is_active: boolean;
                created_at: string;
                updated_at: string;
            }

            interface Course {
                id: number;
                title: string;
                slug: string;
                full_description?: string;
                category_id: number;
                level: 'beginner' | 'intermediate' | 'advanced' | 'all';
                instructor_id: number;
                thumbnail_image?: string;
                preview_video_url?: string;
                price: number;
                currency: string;
                discount_price?: number;
                discount_expires_at?: string;
                duration_hours?: number;
                total_lessons?: number;
                total_quizzes?: number;
                total_assignments?: number;
                max_students?: number;
                current_students?: number;
                enrollment_starts_at?: string;
                enrollment_ends_at?: string;
                status: 'draft' | 'published' | 'archived';
                is_featured: boolean;
                is_free: boolean;
                average_rating?: number;
                total_reviews?: number;
                published_at?: string;
                created_at: string;
                updated_at: string;
            }

            interface Teacher {
                id: number;
                full_name: string;
                speciality?: string;
                experience?: string;
                profile_picture?: string;
                phone?: string;
                is_active: boolean;
                created_at: string;
                updated_at: string;
            }

            interface Payment {
                id: number;
                student_id: number;
                course_id: number;
                amount: number;
                currency: string;
                payment_method: 'bkash' | 'nagad' | 'rocket' | 'bank_transfer';
                status: 'pending' | 'processing' | 'completed' | 'failed' | 'cancelled' | 'refunded';
                transaction_id?: string;
                sender_number?: string;
                receiver_number?: string;
                bank_name?: string;
                account_number?: string;
                branch_name?: string;
                created_at: string;
                updated_at: string;
            }

            interface StudentData extends Student {
                user: User;
            }

            interface CourseData extends Course {
                category?: CourseCategory;
                instructor?: Teacher;
            }

            interface PaymentData extends Payment {
                student: StudentData;
                course: CourseData;
            }

            interface Enrollment {
                id: number;
                student_id: number;
                course_id: number;
                enrolled_at: string;
                enrollment_type: 'paid' | 'free' | 'scholarship' | 'trial';
                payment_id?: number;
                amount_paid: number;
                currency: string;
                payment_status: 'pending' | 'completed' | 'failed' | 'refunded';
                progress_percentage: number;
                lessons_completed: number;
                is_completed: boolean;
                is_active: boolean;
                created_at: string;
                updated_at: string;
            }

            interface EnrollmentData extends Enrollment {
                student: StudentData;
                course: CourseData;
                payment?: PaymentData;
            }

            interface Paginated<T> {
                data: T[];
                current_page: number;
                from: number;
                to: number;
                total: number;
                per_page: number;
                last_page: number;
                prev_page_url: string | null;
                next_page_url: string | null;
                links: {
                    url: string | null;
                    label: string;
                    active: boolean;
                }[];
            }
        }
    }
}
