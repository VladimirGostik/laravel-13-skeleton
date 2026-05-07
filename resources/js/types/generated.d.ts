declare namespace App {
namespace Data {
namespace AuditLogs {
export type ActivityLogDetailData = {
id: number,
log_name: string,
description: string,
event: string | null,
subject_type: string | null,
subject_id: string | null,
causer_name: string | null,
causer_email: string | null,
properties: Record<string, any>,
created_at: string | null,
};
export type ActivityLogIndexFilterData = {
search: undefined | string,
subject_type: undefined | string,
user_filter: undefined | string,
date_from: undefined | string,
date_to: undefined | string,
sort: undefined | string,
perPage: number,
};
export type ActivityLogListItemData = {
id: number,
log_name: string,
description: string,
event: string | null,
subject_type: string | null,
subject_id: string | null,
causer_name: string | null,
causer_email: string | null,
created_at: string | null,
};
}
namespace Auth {
export type LoginData = {
email: string,
password: string,
remember: boolean,
};
export type NewPasswordData = {
token: string,
email: string,
password: string,
password_confirmation: string,
};
export type PasswordResetLinkData = {
email: string,
};
}
namespace Profile {
export type ChangePasswordData = {
current_password: string,
password: string,
password_confirmation: string,
};
export type ProfileUpdateData = {
name: string,
email: string,
locale: string,
userRef: undefined | null,
};
}
namespace Roles {
export type RoleDetailData = {
id: string,
name: string,
is_system: boolean,
permissions: string[],
};
export type RoleListItemData = {
id: string,
name: string,
users_count: number,
permissions_count: number,
is_system: boolean,
can: { view: boolean, edit: boolean, delete: boolean },
};
export type RoleStoreData = {
name: string,
permissions: string[],
};
export type RoleUpdateData = {
name: string,
permissions: string[],
};
}
namespace Users {
export type UserDetailData = {
id: string,
name: string,
email: string,
role: string | null,
is_active: boolean,
locale: string,
email_verified_at: string | null,
created_at: string | null,
};
export type UserIndexFilterData = {
search: undefined | string,
role: undefined | string,
is_active: undefined | string,
sort: undefined | string,
perPage: number,
};
export type UserListItemData = {
id: string,
name: string,
email: string,
role: string | null,
is_active: boolean,
created_at: string | null,
can: { view: boolean, edit: boolean, delete: boolean },
};
export type UserStoreData = {
name: string,
email: string,
password: string,
password_confirmation: string,
role: string,
is_active: boolean,
};
export type UserUpdateData = {
name: string,
email: string,
role: string,
is_active: boolean,
};
}
}
namespace Enums {
export type SupportedLanguage = "sk" | "en";
}
}
