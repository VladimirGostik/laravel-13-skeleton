# Business context

## Project

Laravel 13 + Inertia + Vue 3 skeleton — base scaffold for greenfield apps.

## Goal

Production-grade base application with auth, RBAC, audit log, i18n, users/roles management ready out of the box. Each business entity follows the canonical Resource Recipe (controller → DTO → service → policy → Inertia pages → tests).

## Roles

- **admin** — full access (all permissions, `Gate::before` shortcut)
- **user** — base authenticated role (no default permissions)

## Default permissions

- `view users`, `create users`, `edit users`, `delete users`
- `view roles`, `create roles`, `edit roles`, `delete roles`
- `view audit logs`
- `edit global settings`
- `view api docs`

## Domain entities

None yet — this is a base skeleton. Add business domains via `/feature` once the scaffolding is verified.

## Stakeholders

Internal — this is a reusable starter for future projects.
