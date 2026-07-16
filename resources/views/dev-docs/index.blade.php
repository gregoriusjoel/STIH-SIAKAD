<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevDocs | Workspace</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Marked.js -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <style>
        :root {
            --bg-color: #0c0c0e;
            /* Notion dark page background */
            --sidebar-bg: #121214;
            /* Notion dark sidebar background */
            --border-color: rgba(255, 255, 255, 0.045);
            --text-primary: #fafafa;
            --text-secondary: #a1a1aa;
            --text-muted: #52525b;
            --font-ui: 'Plus Jakarta Sans', sans-serif;
            --font-text: 'Inter', sans-serif;
            --font-code: 'JetBrains Mono', monospace;
            --sidebar-width: 250px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Clean Scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.06);
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        body {
            font-family: var(--font-text);
            background-color: var(--bg-color);
            color: var(--text-primary);
            overflow: hidden;
            height: 100vh;
            display: flex;
        }

        /* ── Notion Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            height: 100%;
            z-index: 10;
        }

        .sidebar-header {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 26px;
            height: 26px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 12px;
        }

        .logo-text h1 {
            font-family: var(--font-ui);
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.2px;
            cursor: pointer;
        }

        .doc-list-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 20px 12px;
        }

        .section-title {
            font-family: var(--font-ui);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            margin-bottom: 12px;
            padding-left: 12px;
            font-weight: 700;
        }

        .doc-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            border-radius: 6px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 4px;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .doc-item:hover {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-primary);
        }

        .doc-item.active {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            font-weight: 600;
        }

        .doc-item-title {
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        .doc-item-title i {
            font-size: 14px;
            color: var(--text-muted);
        }

        .doc-item.active .doc-item-title i {
            color: #ffffff;
        }

        .btn-delete-doc {
            opacity: 0;
            background: transparent;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.15s;
        }

        .doc-item:hover .btn-delete-doc {
            opacity: 0.6;
        }

        .btn-delete-doc:hover {
            opacity: 1 !important;
            background: rgba(239, 68, 68, 0.08);
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border-color);
        }

        .btn-add-doc {
            width: 100%;
            padding: 10px;
            background: transparent;
            border: 1px dashed rgba(255, 255, 255, 0.1);
            color: var(--text-secondary);
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s;
        }

        .btn-add-doc:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* ── Notion Workspace ── */
        .workspace {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow-y: auto;
            background: var(--bg-color);
        }

        /* Notion Top Navigation Bar */
        .top-bar {
            height: 48px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            background: var(--bg-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .breadcrumbs {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
        }

        .crumb-parent {
            color: var(--text-muted);
            cursor: pointer;
            transition: color 0.15s;
        }

        .crumb-parent:hover {
            color: var(--text-primary);
        }

        .crumb-separator {
            color: var(--text-muted);
            font-size: 10px;
        }

        .crumb-active {
            color: var(--text-primary);
        }

        .top-bar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-text {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s;
            font-family: inherit;
            text-decoration: none;
        }

        .btn-text:hover {
            background: rgba(255, 255, 255, 0.04);
            color: var(--text-primary);
        }

        .btn-primary-minimal {
            background: #ffffff;
            border: 1px solid #ffffff;
            color: #09090b;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 6px;
            transition: opacity 0.15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary-minimal:hover {
            opacity: 0.9;
        }

        .btn-copy {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-copy:hover {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text-primary);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Centered Page Style */
        .page-container {
            max-width: 820px;
            width: 100%;
            margin: 0 auto;
            padding: 60px 54px 120px 54px;
            display: flex;
            flex-direction: column;
        }

        .page-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 14px;
        }

        .mode-selector {
            display: flex;
            gap: 4px;
        }

        .mode-tab {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s;
            font-family: inherit;
        }

        .mode-tab:hover {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-primary);
        }

        .mode-tab.active {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            font-weight: 600;
        }

        .status-indicator {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }

        .status-indicator.saving {
            color: #fbbf24;
        }

        .page-title {
            font-family: var(--font-ui);
            font-size: 38px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 32px;
            letter-spacing: -1px;
        }

        .notion-textarea {
            width: 100%;
            min-height: 580px;
            background: transparent;
            border: none;
            outline: none;
            resize: none;
            color: #e2e8f0;
            font-family: var(--font-text);
            font-size: 15px;
            line-height: 1.85;
            padding: 0;
            overflow-y: visible;
        }

        /* Split Layout for Todo */
        .todo-split-layout {
            display: flex;
            gap: 24px;
            width: 100%;
            align-items: stretch;
            min-height: 580px;
        }

        @media (max-width: 992px) {
            .todo-split-layout {
                flex-direction: column;
            }
        }

        .todo-pane {
            flex: 1;
            background: rgba(255, 255, 255, 0.015);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: all 0.25s ease;
        }

        .todo-pane:hover {
            border-color: rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.025);
        }

        .todo-pane.checklist-pane {
            border-left: 3px solid #10b981;
        }

        .todo-pane.notepad-pane {
            border-left: 3px solid #6366f1;
        }

        .pane-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
            margin-bottom: 4px;
        }

        .todo-pane.checklist-pane .pane-title {
            color: #10b981;
        }

        .todo-pane.notepad-pane .pane-title {
            color: #6366f1;
        }

        .todo-pane .notion-textarea {
            min-height: 480px;
        }

        .preview-content {
            font-family: var(--font-text);
            line-height: 1.8;
            font-size: 15px;
            color: #cbd5e1;
        }

        /* ── Notion Markdown preview styling ── */
        .markdown-body h1,
        .markdown-body h2,
        .markdown-body h3,
        .markdown-body h4 {
            color: #ffffff;
            font-family: var(--font-ui);
            font-weight: 700;
            margin-top: 36px;
            margin-bottom: 14px;
            letter-spacing: -0.4px;
        }

        .markdown-body h1 {
            font-size: 28px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
            margin-top: 0;
        }

        .markdown-body h2 {
            font-size: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            padding-bottom: 6px;
        }

        .markdown-body h3 {
            font-size: 16px;
        }

        .markdown-body p {
            margin-bottom: 18px;
        }

        .markdown-body ul,
        .markdown-body ol {
            margin-bottom: 18px;
            padding-left: 20px;
        }

        .markdown-body li {
            margin-bottom: 8px;
        }

        .markdown-body code {
            font-family: var(--font-code);
            background: rgba(255, 255, 255, 0.05);
            color: #e4e4e7;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 13px;
        }

        .markdown-body pre {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px;
            overflow-x: auto;
            margin-bottom: 18px;
        }

        .markdown-body pre code {
            background: transparent;
            color: #e2e8f0;
            padding: 0;
            font-size: 13px;
        }

        .markdown-body blockquote {
            border-left: 3px solid #ffffff;
            background: rgba(255, 255, 255, 0.015);
            padding: 12px 20px;
            margin-bottom: 18px;
            color: #a1a1aa;
            font-style: italic;
        }

        .markdown-body hr {
            border: none;
            border-top: 1px solid var(--border-color);
            margin: 32px 0;
        }

        /* ── Right side sliding Help Drawer ── */
        .drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .drawer-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .drawer {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 440px;
            background: #0f0f11;
            border-left: 1px solid var(--border-color);
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            padding: 30px;
        }

        .drawer-overlay.show .drawer {
            transform: translateX(0);
        }

        .drawer-header {
            margin-bottom: 24px;
        }

        .drawer-header h3 {
            font-family: var(--font-ui);
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .drawer-header p {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .drawer-content {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 24px;
        }

        .help-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .help-table th,
        .help-table td {
            padding: 10px 0;
            text-align: left;
        }

        .help-table th {
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 1px;
            border-bottom: 1px solid var(--border-color);
        }

        .help-table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        }

        .help-table td:first-child {
            font-weight: 500;
            color: var(--text-primary);
        }

        .help-table td:last-child {
            font-family: var(--font-code);
            color: #d4d4d8;
        }

        /* ── Centered Modals ── */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transition: all 0.2s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .modal {
            background: #18181b;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            width: 400px;
            padding: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
            transform: scale(0.95);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .modal-overlay.show .modal {
            transform: scale(1);
        }

        .modal-header h3 {
            font-family: var(--font-ui);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .modal-header p {
            font-size: 12.5px;
            color: var(--text-secondary);
            margin-bottom: 18px;
        }

        .modal-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            padding: 10px 12px;
            border-radius: 6px;
            color: white;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            margin-bottom: 20px;
        }

        .modal-input:focus {
            border-color: #ffffff;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-modal {
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-modal-secondary {
            background: transparent;
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .btn-modal-primary {
            background: #ffffff;
            color: #09090b;
        }

        /* ── Premium Minimalist Toast ── */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #18181b;
            border: 1px solid var(--border-color);
            padding: 12px 18px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 999;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast-icon {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            background: #ffffff;
            color: #09090b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }

        .toast-icon.error {
            background: #ef4444;
            color: white;
        }

        .toast-message {
            font-size: 13px;
            font-weight: 600;
        }

        /* ── Welcome Screen ── */
        .welcome-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow-y: auto;
        }

        .welcome-content {
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .welcome-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .welcome-title {
            font-family: var(--font-ui);
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .welcome-subtitle {
            font-size: 14.5px;
            color: var(--text-secondary);
            line-height: 1.65;
            margin-bottom: 40px;
        }

        .welcome-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            text-align: left;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.015);
            border: 1px solid var(--border-color);
            padding: 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .welcome-card:hover {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .welcome-card i {
            font-size: 18px;
            color: var(--text-secondary);
            transition: color 0.2s;
        }

        .welcome-card:hover i {
            color: #ffffff;
        }

        .welcome-card-title {
            font-size: 14.5px;
            font-weight: 600;
            color: #ffffff;
            text-transform: capitalize;
        }

        .welcome-card-desc {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* ── Kanban Board Styles ── */
        .kanban-board-wrapper {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 120px);
            padding: 20px 0;
            overflow-x: auto;
            width: 100%;
        }

        .kanban-board {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            min-width: max-content;
            padding-bottom: 20px;
        }

        .kanban-column {
            flex: 1;
            min-width: 280px;
            max-width: 320px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            max-height: 100%;
            padding: 16px;
            transition: background 0.2s, border-color 0.2s;
            cursor: grab;
        }

        .kanban-column:active {
            cursor: grabbing;
        }

        .kanban-column.col-dragging {
            opacity: 0.35;
            border: 2px dashed rgba(255, 255, 255, 0.25);
            background: transparent;
        }

        .kanban-column.drag-over {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .kanban-column.col-drag-over {
            border-left: 2px solid #6366f1;
            background: rgba(99, 102, 241, 0.03);
        }

        .kanban-column-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .kanban-column-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .kanban-column-title {
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
        }

        .kanban-card-count {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text-secondary);
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
        }

        .kanban-btn-add-card {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .kanban-btn-add-card:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }

        .kanban-btn-add-card-bottom {
            width: 100%;
            background: rgba(255, 255, 255, 0.02);
            border: 1px dashed rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: var(--text-secondary);
            padding: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 12px;
            transition: all 0.2s;
        }

        .kanban-btn-add-card-bottom:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        .kanban-add-column-wrapper {
            width: 280px;
            min-width: 280px;
            max-width: 320px;
            background: rgba(255, 255, 255, 0.015);
            border: 1px dashed rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 16px;
            align-self: flex-start;
            transition: all 0.2s;
        }

        .kanban-add-column-wrapper:hover {
            border-color: rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.025);
        }

        .kanban-add-column-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            height: 40px;
            transition: color 0.15s;
        }

        .kanban-add-column-btn:hover {
            color: #ffffff;
        }

        .kanban-add-column-form {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .kanban-cards-list {
            flex: 1;
            overflow-y: auto;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding-right: 4px;
        }

        .kanban-card {
            background: #121214;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 14px;
            cursor: grab;
            transition: transform 0.15s, border-color 0.15s, box-shadow 0.15s;
            position: relative;
        }

        .kanban-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .kanban-card.dragging {
            opacity: 0.3;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            background: none;
        }

        .kanban-card-title {
            font-size: 13.5px;
            font-weight: 600;
            color: #ffffff;
            line-height: 1.4;
            margin-bottom: 6px;
            word-wrap: break-word;
        }

        .kanban-card-desc {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: 12px;
            word-wrap: break-word;
        }

        .kanban-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .kanban-card-date {
            font-size: 11px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .kanban-card-actions {
            display: flex;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.15s;
        }

        .kanban-card:hover .kanban-card-actions {
            opacity: 1;
        }

        .kanban-card-action-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 12px;
            padding: 2px;
            transition: color 0.15s;
        }

        .kanban-card-action-btn.edit:hover {
            color: #ffffff;
        }

        .kanban-card-action-btn.delete:hover {
            color: #ef4444;
        }

        /* ── Kanban Board Dialog/Modal Styles ── */
        .kanban-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .kanban-modal {
            background: #121214;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            width: 100%;
            max-width: 440px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            gap: 20px;
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .kanban-modal-header {
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
        }

        .kanban-form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .kanban-form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kanban-form-input,
        .kanban-form-textarea {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: #ffffff;
            padding: 10px 12px;
            font-size: 13.5px;
            font-family: var(--font-text);
            outline: none;
            transition: border-color 0.15s;
        }

        .kanban-form-input:focus,
        .kanban-form-textarea:focus {
            border-color: rgba(255, 255, 255, 0.2);
        }

        .kanban-form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .kanban-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 10px;
        }

        .kanban-btn-cancel {
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
        }

        .kanban-btn-cancel:hover {
            background: rgba(255, 255, 255, 0.03);
            color: #ffffff;
        }

        .kanban-btn-save {
            background: #ffffff;
            border: 1px solid #ffffff;
            color: #09090b;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.15s;
        }

        .kanban-btn-save:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <!-- Left Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">
                <i class="fa-solid fa-square-terminal"></i>
            </div>
            <div class="logo-text">
                <h1 onclick="window.location.href='/dev-docs'">DevDocs</h1>
            </div>
        </div>

        <div class="doc-list-wrapper">
            <h2 class="section-title">Workspace</h2>
            <div class="doc-item {{ $activeDoc === 'todo.md' ? 'active' : '' }}" onclick="switchDoc('todo.md')">
                <span class="doc-item-title">
                    <i class="fa-solid fa-list-check"></i>
                    Daftar Tugas (To-Do)
                </span>
            </div>
            <div class="doc-item {{ $activeDoc === 'board.json' ? 'active' : '' }}" onclick="switchDoc('board.json')">
                <span class="doc-item-title">
                    <i class="fa-solid fa-chalkboard"></i>
                    Papan Kanban (Trello)
                </span>
            </div>

            <h2 class="section-title" style="margin-top: 24px;">Dokumentasi</h2>
            @foreach($documents as $doc)
                @php
                    $icon = match ($doc) {
                        'changelog.md' => 'fa-solid fa-history',
                        'todo.md' => 'fa-solid fa-list-check',
                        'panduan.md' => 'fa-solid fa-graduation-cap',
                        'credentials.md' => 'fa-solid fa-key',
                        default => 'fa-regular fa-file-lines'
                    };
                @endphp
                <div class="doc-item {{ $activeDoc === $doc ? 'active' : '' }}" onclick="switchDoc('{{ $doc }}')">
                    <span class="doc-item-title">
                        <i class="{{ $icon }}"></i>
                        {{ basename($doc, '.md') }}
                    </span>
                    @if($doc !== 'changelog.md')
                        <button class="btn-delete-doc" onclick="event.stopPropagation(); confirmDeleteDoc('{{ $doc }}')"
                            title="Hapus Dokumen">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="sidebar-footer">
            <button class="btn-add-doc" onclick="openCreateModal()">
                <i class="fa-solid fa-plus"></i>
                Dokumen Baru
            </button>
        </div>
    </div>

    <!-- Main Workspace -->
    <div class="workspace">
        <!-- Top Navigation Bar -->
        <div class="top-bar">
            <div class="breadcrumbs">
                <span class="crumb-parent" onclick="window.location.href='/dev-docs'">Docs</span>
                @if($activeDoc)
                    <span class="crumb-separator">/</span>
                    <span
                        class="crumb-active">{{ $activeDoc === 'board.json' ? 'Papan Kanban' : ($activeDoc === 'todo.md' ? 'Daftar Tugas' : basename($activeDoc, '.md')) }}</span>
                @endif
            </div>
            <div class="top-bar-actions">
                <button class="btn-text" onclick="openHelpDrawer()">
                    <i class="fa-regular fa-circle-question"></i> Panduan
                </button>
                <a href="/admin/dashboard" class="btn-text">
                    <i class="fa-solid fa-arrow-left"></i> Dashboard
                </a>
                @if($activeDoc && $activeDoc !== 'board.json')
                    <button class="btn-primary-minimal" onclick="saveDocument()">
                        <i class="fa-solid fa-check"></i> Simpan
                    </button>
                @endif
            </div>
        </div>

        @if($activeDoc === 'board.json')
            <!-- Kanban Board Content -->
            <div class="page-container" style="max-width: 100%; padding: 40px 40px 120px 40px;">
                <div class="page-meta" style="margin-bottom: 24px;">
                    <div
                        style="font-size: 14px; color: var(--text-secondary); display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-chalkboard"></i>
                        <span>Papan Pemantauan Revisi & Progres</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <button class="btn-primary-minimal" onclick="openAddColumnModalGlobal()">
                            <i class="fa-solid fa-plus"></i> Tambah Kolom Baru
                        </button>
                        <div class="status-indicator" id="status-badge">
                            <i class="fa-solid fa-circle-check"></i> Tersimpan
                        </div>
                    </div>
                </div>

                <h1 class="page-title" style="margin-bottom: 20px;">Papan Kanban Revisi</h1>

                <!-- Kanban Board Columns Container -->
                <div class="kanban-board-wrapper">
                    <div class="kanban-board" id="kanban-board-container">
                        <!-- Column templates will be rendered by JS dynamically -->
                    </div>
                </div>
            </div>

        @elseif($activeDoc)
            <!-- Notion Page Content -->
            <div class="page-container"
                style="{{ $activeDoc === 'todo.md' ? 'max-width: 1400px; padding: 40px 40px 120px 40px;' : '' }}">
                <div class="page-meta">
                    @if($activeDoc !== 'todo.md')
                        <div class="mode-selector">
                            <button class="mode-tab active" id="tab-read" onclick="setMode('read')">
                                <i class="fa-regular fa-file-lines"></i> Baca
                            </button>
                            <button class="mode-tab" id="tab-edit" onclick="setMode('edit')">
                                <i class="fa-regular fa-edit"></i> Edit
                            </button>
                        </div>
                    @else
                        <div
                            style="font-size: 13.5px; color: var(--text-secondary); display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>Tulis dan sunting daftar tugas Anda langsung di bawah ini.</span>
                        </div>
                    @endif
                    <div style="display: flex; align-items: center; gap: 12px;">
                        @if($activeDoc !== 'todo.md')
                            <button class="btn-copy" onclick="copyMarkdownContent()" title="Salin format Markdown">
                                <i class="fa-regular fa-copy"></i> Salin Konten
                            </button>
                        @endif
                        <div class="status-indicator" id="status-badge">
                            <i class="fa-solid fa-circle-check"></i> Tersimpan
                        </div>
                    </div>
                </div>

                <h1 class="page-title">
                    {{ $activeDoc === 'todo.md' ? 'Daftar Tugas (To-Do List)' : ucwords(str_replace(['-', '_'], ' ', basename($activeDoc, '.md'))) }}
                </h1>

                <div class="page-content">
                    @if($activeDoc === 'todo.md')
                        <!-- Split Workspace for Todo -->
                        <div class="todo-split-layout">
                            <!-- Left: Interactive Checklist -->
                            <div class="todo-pane checklist-pane">
                                <div class="pane-title">
                                    <i class="fa-solid fa-list-check"></i>
                                    Data Perubahan
                                </div>
                                <div id="read-view" class="preview-content markdown-body">
                                    <!-- Rendered checkboxes -->
                                </div>
                            </div>

                            <!-- Right: Notepad Editor -->
                            <div class="todo-pane notepad-pane">
                                <div class="pane-title">
                                    <i class="fa-solid fa-keyboard"></i>
                                    Notepad Pengeditan
                                </div>
                                <div id="edit-view" style="display: block;">
                                    <textarea id="editor" class="notion-textarea" spellcheck="false"
                                        placeholder="Tulis tugas harian atau To-Do list Anda di sini...">{{ $content }}</textarea>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Read Mode View -->
                        <div id="read-view" class="preview-content markdown-body">
                            <!-- Rendered markdown goes here -->
                        </div>

                        <!-- Edit Mode Textarea -->
                        <div id="edit-view" style="display: none;">
                            <textarea id="editor" class="notion-textarea" spellcheck="false"
                                placeholder="Tulis catatan atau progres hari ini...">{{ $content }}</textarea>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Welcome / Landing Screen -->
            <div class="welcome-container">
                <div class="welcome-content">
                    <div class="welcome-icon">👋</div>
                    <h1 class="welcome-title">DevDocs Workspace</h1>
                    <p class="welcome-subtitle">Ruang kerja dokumentasi pengembangan lokal Anda. Silakan pilih dokumen di
                        menu sebelah kiri untuk memulai atau buat dokumen baru secara instan.</p>

                    <div class="welcome-grid">
                        <div class="welcome-card" onclick="switchDoc('board.json')">
                            <i class="fa-solid fa-chalkboard"></i>
                            <div class="welcome-card-title">Papan Kanban</div>
                            <div class="welcome-card-desc">Buka papan pemantauan revisi</div>
                        </div>
                        @foreach($documents as $doc)
                            @php
                                $icon = match ($doc) {
                                    'changelog.md' => 'fa-solid fa-history',
                                    'todo.md' => 'fa-solid fa-list-check',
                                    'panduan.md' => 'fa-solid fa-graduation-cap',
                                    'credentials.md' => 'fa-solid fa-key',
                                    default => 'fa-regular fa-file-lines'
                                };
                            @endphp
                            <div class="welcome-card" onclick="switchDoc('{{ $doc }}')">
                                <i class="{{ $icon }}"></i>
                                <div class="welcome-card-title">{{ basename($doc, '.md') }}</div>
                                <div class="welcome-card-desc">Buka dokumen ini</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Kanban Card Add/Edit Modal -->
    <div class="kanban-modal-overlay" id="kanban-modal-overlay">
        <div class="kanban-modal">
            <div class="kanban-modal-header" id="kanban-modal-title">Tambah Kartu Baru</div>

            <input type="hidden" id="kanban-card-id">
            <input type="hidden" id="kanban-column-id">

            <div class="kanban-form-group">
                <label class="kanban-form-label" for="kanban-input-title">Judul Tugas / Revisi</label>
                <input type="text" id="kanban-input-title" class="kanban-form-input"
                    placeholder="Masukkan judul revisi...">
            </div>

            <div class="kanban-form-group">
                <label class="kanban-form-label" for="kanban-input-desc">Deskripsi Detail</label>
                <textarea id="kanban-input-desc" class="kanban-form-textarea"
                    placeholder="Detail perbaikan atau catatan tambahan..."></textarea>
            </div>

            <div class="kanban-modal-footer">
                <button class="kanban-btn-cancel" onclick="closeKanbanModal()">Batal</button>
                <button class="kanban-btn-save" onclick="saveKanbanCard()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Right-side Sliding Help Drawer -->
    <div class="drawer-overlay" id="help-drawer-overlay" onclick="closeHelpDrawer()">
        <div class="drawer" onclick="event.stopPropagation()">
            <div class="drawer-header">
                <h3>Panduan Markdown</h3>
                <p>Panduan singkat format pemformatan dokumen.</p>
            </div>

            <div class="drawer-content">
                <table class="help-table">
                    <thead>
                        <tr>
                            <th>Tampilan</th>
                            <th>Penulisan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Judul Utama (H1)</td>
                            <td># Judul Utama</td>
                        </tr>
                        <tr>
                            <td>Sub Judul (H2)</td>
                            <td>## Sub Judul</td>
                        </tr>
                        <tr>
                            <td>Sub Judul (H3)</td>
                            <td>### Sub Judul</td>
                        </tr>
                        <tr>
                            <td>Daftar List / Strip</td>
                            <td>- Item Pertama<br>- Item Kedua</td>
                        </tr>
                        <tr>
                            <td>Garis Pemisah (Line)</td>
                            <td>--- (tiga tanda minus)</td>
                        </tr>
                        <tr>
                            <td>Ganti Baris Biasa</td>
                            <td>Ketik 2 spasi di ujung, lalu Enter</td>
                        </tr>
                        <tr>
                            <td>Ganti Paragraf</td>
                            <td>Tekan tombol Enter 2 kali</td>
                        </tr>
                        <tr>
                            <td>Teks Tebal</td>
                            <td>**teks tebal**</td>
                        </tr>
                        <tr>
                            <td>Teks Miring</td>
                            <td>*teks miring*</td>
                        </tr>
                        <tr>
                            <td>Kutipan Sorot</td>
                            <td>&gt; kalimat kutipan</td>
                        </tr>
                        <tr>
                            <td>Code Inline</td>
                            <td>`kode_anda`</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="modal-footer" style="padding-top: 10px;">
                <button class="btn-modal btn-modal-primary" style="width: 100%; justify-content: center;"
                    onclick="closeHelpDrawer()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Buat Dokumen Baru -->
    <div class="modal-overlay" id="create-modal-overlay" onclick="closeCreateModal()">
        <div class="modal" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3>Dokumen Baru</h3>
                <p>Nama berkas hanya boleh berisi huruf, angka, strip (-), dan underscore (_).</p>
            </div>
            <input type="text" class="modal-input" id="new-doc-name" placeholder="misal: todo-besok"
                onkeydown="if(event.key === 'Enter') submitCreateDoc()">
            <div class="modal-footer">
                <button class="btn-modal btn-modal-secondary" onclick="closeCreateModal()">Batal</button>
                <button class="btn-modal btn-modal-primary" onclick="submitCreateDoc()">Buat Dokumen</button>
            </div>
        </div>
    </div>

    <!-- Modal Buat Kolom Baru -->
    <div class="modal-overlay" id="column-modal-overlay" onclick="closeAddColumnModal()">
        <div class="modal" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3>Tambah Kolom Baru</h3>
                <p>Masukkan nama untuk kolom papan kanban baru Anda.</p>
            </div>
            <input type="text" class="modal-input" id="new-column-name" placeholder="misal: Pending / Tertunda"
                onkeydown="if(event.key === 'Enter') submitAddColumnGlobal()">
            <div class="modal-footer">
                <button class="btn-modal btn-modal-secondary" onclick="closeAddColumnModal()">Batal</button>
                <button class="btn-modal btn-modal-primary" onclick="submitAddColumnGlobal()">Tambah Kolom</button>
            </div>
        </div>
    </div>

    <!-- Toast Success Notification -->
    <div class="toast" id="toast">
        <div class="toast-icon" id="toast-icon">
            <i class="fa-solid fa-check"></i>
        </div>
        <div class="toast-message" id="toast-message">Perubahan berhasil disimpan!</div>
    </div>

    <script>
        const editor = document.getElementById('editor');
        const preview = document.getElementById('read-view');
        const statusBadge = document.getElementById('status-badge');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const activeDoc = '{{ $activeDoc }}';
        let activeMode = 'read'; // 'read' or 'edit'
        let isDirty = false;

        // Set mode view/edit
        function setMode(mode) {
            if (!activeDoc) return;

            // Force edit mode for todo.md notepad view
            if (activeDoc === 'todo.md') {
                mode = 'edit';
            }

            activeMode = mode;
            const readTab = document.getElementById('tab-read');
            const editTab = document.getElementById('tab-edit');
            const readView = document.getElementById('read-view');
            const editView = document.getElementById('edit-view');

            if (mode === 'read') {
                if (readTab) readTab.classList.add('active');
                if (editTab) editTab.classList.remove('active');
                if (readView) readView.style.display = 'block';
                if (editView) editView.style.display = 'none';
                updatePreview();
            } else {
                if (readTab) readTab.classList.remove('active');
                if (editTab) editTab.classList.add('active');
                if (activeDoc !== 'todo.md') {
                    if (readView) readView.style.display = 'none';
                }
                if (editView) editView.style.display = 'block';
                editor.focus();
                autoGrowTextArea();
            }
        }

        // Render Markdown Live
        function updatePreview() {
            if (!activeDoc || !editor || !preview) return;
            const rawMarkdown = editor.value;
            preview.innerHTML = marked.parse(rawMarkdown);
            enableInteractiveCheckboxes();
        }

        // Enable interactive checkboxes in markdown list items
        function enableInteractiveCheckboxes() {
            if (!preview) return;
            const checkboxes = preview.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach((cb, index) => {
                cb.removeAttribute('disabled');
                cb.style.cursor = 'pointer';
                cb.style.pointerEvents = 'auto';
                cb.style.opacity = '1';

                cb.addEventListener('change', () => {
                    toggleMarkdownCheckbox(index, cb.checked);
                });
            });
        }

        // Toggle the target index checkbox in editor raw text and save
        function toggleMarkdownCheckbox(targetIndex, isChecked) {
            if (!editor) return;
            const content = editor.value;

            // Regex to find all markdown task list checkboxes: - [ ] or * [ ] or - [x] or - [X]
            const regex = /((?:-|\*|\d+\.)\s*\[)([ xX])(\]\s+)/g;

            let matchCount = 0;
            const newContent = content.replace(regex, (match, prefix, state, suffix) => {
                if (matchCount === targetIndex) {
                    matchCount++;
                    return prefix + (isChecked ? 'x' : ' ') + suffix;
                }
                matchCount++;
                return match;
            });

            editor.value = newContent;
            updatePreview();
            saveDocument(true);
        }

        // Textarea Auto-grow based on content height
        function autoGrowTextArea() {
            if (!editor) return;
            editor.style.height = 'auto';
            editor.style.height = (editor.scrollHeight + 50) + 'px';
        }

        let saveTimeout;
        if (editor) {
            editor.addEventListener('input', () => {
                autoGrowTextArea();
                updatePreview(); // Update preview checklist on the left live as you type!

                if (!isDirty) {
                    isDirty = true;
                    statusBadge.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Perubahan belum disimpan';
                    statusBadge.className = 'status-indicator saving';
                }

                // Debounce autosave: save 1 second after user stops typing
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(() => {
                    saveDocument(true);
                }, 1000);
            });
        }

        // Initialize preview load
        if (activeDoc) {
            updatePreview();
            if (activeDoc === 'todo.md') {
                setMode('edit');
            }
        }

        // Keyboard Save Action (Ctrl+S / Cmd+S)
        document.addEventListener('keydown', (e) => {
            if (activeDoc && (e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                saveDocument();
            }
        });

        // Copy clean rendered text to clipboard (stripping Markdown tags, with HTTP/IP fallback support)
        function copyMarkdownContent() {
            if (!activeDoc || !preview || !editor) return;

            // Make sure the preview contains the latest input
            updatePreview();

            // Get clean text (strips markdown tags like #, **, and HTML tags naturally)
            const contentToCopy = preview.innerText.trim();

            // Check if Clipboard API is supported and context is secure (HTTPS/localhost)
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(contentToCopy)
                    .then(() => {
                        showToast('Konten bersih berhasil disalin ke clipboard!', false);
                    })
                    .catch(err => {
                        fallbackCopyText(contentToCopy);
                    });
            } else {
                // Fallback for non-secure HTTP / IP contexts (e.g. http://192.168.x.x:8000)
                fallbackCopyText(contentToCopy);
            }
        }

        // Classic copy fallback
        function fallbackCopyText(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;

            // Hide element from view
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            textArea.style.opacity = "0";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showToast('Konten berhasil disalin ke clipboard!', false);
                } else {
                    showToast('Gagal menyalin konten', true);
                }
            } catch (err) {
                showToast('Gagal menyalin konten', true);
                console.error('Fallback copy failed: ', err);
            }

            document.body.removeChild(textArea);
        }

        // Switch active document
        function switchDoc(filename) {
            if (isDirty && !confirm('Ada perubahan yang belum disimpan. Yakin ingin berpindah dokumen?')) {
                return;
            }
            window.location.href = '?doc=' + filename;
        }

        // Save active document
        function saveDocument(silent = false) {
            if (!activeDoc) return;
            if (!silent) {
                statusBadge.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...';
                statusBadge.className = 'status-indicator saving';
            }

            fetch('{{ route("dev-docs.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    doc: activeDoc,
                    content: editor.value
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        isDirty = false;
                        statusBadge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Tersimpan';
                        statusBadge.className = 'status-indicator';
                        if (!silent) {
                            showToast(data.message, false);
                        }
                    } else {
                        if (!silent) {
                            showToast(data.message || 'Gagal menyimpan dokumen', true);
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    statusBadge.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Gagal menyimpan';
                    statusBadge.className = 'status-indicator saving';
                    if (!silent) {
                        showToast('Terjadi kesalahan jaringan', true);
                    }
                });
        }

        // Toast display helper
        function showToast(message, isError) {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toast-icon');
            const msg = document.getElementById('toast-message');

            msg.innerText = message;
            if (isError) {
                icon.className = 'toast-icon error';
                icon.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            } else {
                icon.className = 'toast-icon';
                icon.innerHTML = '<i class="fa-solid fa-check"></i>';
            }

            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Create doc modal handlers
        function openCreateModal() {
            document.getElementById('create-modal-overlay').classList.add('show');
            document.getElementById('new-doc-name').focus();
        }

        // Close doc modal
        function closeCreateModal() {
            document.getElementById('create-modal-overlay').classList.remove('show');
            document.getElementById('new-doc-name').value = '';
        }

        // Submit new doc creation
        function submitCreateDoc() {
            const nameInput = document.getElementById('new-doc-name');
            const name = nameInput.value.trim();

            if (!name) {
                showToast('Nama dokumen tidak boleh kosong', true);
                return;
            }

            fetch('{{ route("dev-docs.create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ name: name })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closeCreateModal();
                        showToast(data.message, false);
                        setTimeout(() => {
                            window.location.href = '?doc=' + data.doc;
                        }, 500);
                    } else {
                        showToast(data.message || 'Gagal membuat dokumen', true);
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Terjadi kesalahan jaringan', true);
                });
        }

        // Delete active document
        function confirmDeleteDoc(filename) {
            if (confirm(`Yakin ingin menghapus dokumen "${filename.replace('.md', '')}"? Tindakan ini tidak bisa dibatalkan.`)) {
                fetch('{{ route("dev-docs.delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ doc: filename })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, false);
                            setTimeout(() => {
                                window.location.href = '/dev-docs';
                            }, 500);
                        } else {
                            showToast(data.message || 'Gagal menghapus dokumen', true);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('Terjadi kesalahan jaringan', true);
                    });
            }
        }

        // Right Help Drawer Toggle Handlers
        function openHelpDrawer() {
            document.getElementById('help-drawer-overlay').classList.add('show');
        }

        function closeHelpDrawer() {
            document.getElementById('help-drawer-overlay').classList.remove('show');
        }

        @if($activeDoc === 'board.json')
            // Kanban Board State and Functions
            let boardData = null;
            try {
                boardData = JSON.parse({!! json_encode($content) !!});
            } catch (e) {
                console.error("Failed to parse board data, initializing default.", e);
                boardData = { columns: [] };
            }

            // Render Kanban board from memory
            function renderKanbanBoard() {
                const container = document.getElementById('kanban-board-container');
                if (!container) return;
                container.innerHTML = '';

                boardData.columns.forEach(column => {
                    const columnEl = document.createElement('div');
                    columnEl.className = 'kanban-column';
                    columnEl.id = `col-${column.id}`;
                    columnEl.setAttribute('data-id', column.id);
                    columnEl.draggable = true;

                    // Column drag and drop event listeners
                    columnEl.addEventListener('dragstart', handleColDragStart);
                    columnEl.addEventListener('dragend', handleColDragEnd);
                    columnEl.addEventListener('dragover', handleDragOver);
                    columnEl.addEventListener('dragenter', handleDragEnter);
                    columnEl.addEventListener('dragleave', handleDragLeave);
                    columnEl.addEventListener('drop', handleDrop);

                    // Column Header
                    const headerEl = document.createElement('div');
                    headerEl.className = 'kanban-column-header';

                    const leftEl = document.createElement('div');
                    leftEl.className = 'kanban-column-left';

                    const titleEl = document.createElement('h3');
                    titleEl.className = 'kanban-column-title';
                    titleEl.textContent = column.title;

                    const countEl = document.createElement('span');
                    countEl.className = 'kanban-card-count';
                    countEl.textContent = column.cards.length;

                    leftEl.appendChild(titleEl);
                    leftEl.appendChild(countEl);

                    // Right actions (Add card & Delete column)
                    const rightActionsEl = document.createElement('div');
                    rightActionsEl.style.display = 'flex';
                    rightActionsEl.style.alignItems = 'center';
                    rightActionsEl.style.gap = '4px';

                    const btnAdd = document.createElement('button');
                    btnAdd.className = 'kanban-btn-add-card';
                    btnAdd.innerHTML = '<i class="fa-solid fa-plus"></i>';
                    btnAdd.title = 'Tambah Kartu';
                    btnAdd.onclick = (e) => {
                        e.stopPropagation();
                        openAddCardModal(column.id);
                    };
                    rightActionsEl.appendChild(btnAdd);

                    const btnDeleteCol = document.createElement('button');
                    btnDeleteCol.className = 'kanban-btn-add-card';
                    btnDeleteCol.innerHTML = '<i class="fa-regular fa-trash-can"></i>';
                    btnDeleteCol.title = 'Hapus Kolom';
                    btnDeleteCol.style.color = 'var(--text-muted)';
                    btnDeleteCol.onmouseover = () => btnDeleteCol.style.color = '#ef4444';
                    btnDeleteCol.onmouseout = () => btnDeleteCol.style.color = 'var(--text-muted)';
                    btnDeleteCol.onclick = (e) => {
                        e.stopPropagation();
                        deleteKanbanColumn(column.id, column.title);
                    };
                    rightActionsEl.appendChild(btnDeleteCol);

                    headerEl.appendChild(leftEl);
                    headerEl.appendChild(rightActionsEl);

                    // Cards list container
                    const listEl = document.createElement('div');
                    listEl.className = 'kanban-cards-list';
                    listEl.id = `cards-${column.id}`;

                    column.cards.forEach(card => {
                        const cardEl = document.createElement('div');
                        cardEl.className = 'kanban-card';
                        cardEl.draggable = true;
                        cardEl.id = card.id;
                        cardEl.setAttribute('data-col-id', column.id);
                        cardEl.addEventListener('dragstart', handleDragStart);
                        cardEl.addEventListener('dragend', handleDragEnd);

                        const cardTitle = document.createElement('div');
                        cardTitle.className = 'kanban-card-title';
                        cardTitle.textContent = card.title;

                        const cardDesc = document.createElement('div');
                        cardDesc.className = 'kanban-card-desc';
                        cardDesc.textContent = card.desc || '';

                        const cardFooter = document.createElement('div');
                        cardFooter.className = 'kanban-card-footer';

                        const cardDate = document.createElement('div');
                        cardDate.className = 'kanban-card-date';
                        cardDate.innerHTML = `<i class="fa-regular fa-calendar"></i> <span>${card.date || ''}</span>`;

                        const cardActions = document.createElement('div');
                        cardActions.className = 'kanban-card-actions';

                        const btnEdit = document.createElement('button');
                        btnEdit.className = 'kanban-card-action-btn edit';
                        btnEdit.innerHTML = '<i class="fa-regular fa-edit"></i>';
                        btnEdit.title = 'Edit Kartu';
                        btnEdit.onclick = (e) => {
                            e.stopPropagation();
                            openEditCardModal(column.id, card.id);
                        };

                        const btnDel = document.createElement('button');
                        btnDel.className = 'kanban-card-action-btn delete';
                        btnDel.innerHTML = '<i class="fa-regular fa-trash-can"></i>';
                        btnDel.title = 'Hapus Kartu';
                        btnDel.onclick = (e) => {
                            e.stopPropagation();
                            deleteKanbanCard(column.id, card.id);
                        };

                        cardActions.appendChild(btnEdit);
                        cardActions.appendChild(btnDel);

                        cardFooter.appendChild(cardDate);
                        cardFooter.appendChild(cardActions);

                        cardEl.appendChild(cardTitle);
                        if (card.desc) {
                            cardEl.appendChild(cardDesc);
                        }
                        cardEl.appendChild(cardFooter);

                        listEl.appendChild(cardEl);
                    });

                    columnEl.appendChild(headerEl);
                    columnEl.appendChild(listEl);

                    // Append prominent "+ Tambah Kartu" button at the bottom of the column
                    const btnAddBottom = document.createElement('button');
                    btnAddBottom.className = 'kanban-btn-add-card-bottom';
                    btnAddBottom.innerHTML = '<i class="fa-solid fa-plus"></i> Tambah Kartu';
                    btnAddBottom.onclick = (e) => {
                        e.stopPropagation();
                        openAddCardModal(column.id);
                    };
                    columnEl.appendChild(btnAddBottom);

                    container.appendChild(columnEl);
                });

                // Append the "Add Column" block at the end of `#kanban-board-container`
                const addColWrapper = document.createElement('div');
                addColWrapper.className = 'kanban-add-column-wrapper';
                addColWrapper.innerHTML = `
                    <div class="kanban-add-column-btn" id="add-column-trigger" onclick="openAddColumnModalGlobal()">
                        <i class="fa-solid fa-plus"></i> Tambah Kolom Baru
                    </div>
                `;
                container.appendChild(addColWrapper);
            }

            // Drag & Drop Handlers
            let draggedCardId = null;
            let sourceColumnId = null;
            let draggedColId = null;

            function handleDragStart(e) {
                e.stopPropagation(); // Prevent column drag from triggering
                draggedCardId = this.id;
                sourceColumnId = this.getAttribute('data-col-id');
                this.classList.add('dragging');

                // Set drag data
                e.dataTransfer.setData('text/plain', this.id);
                e.dataTransfer.effectAllowed = 'move';
            }

            function handleDragEnd(e) {
                this.classList.remove('dragging');
                document.querySelectorAll('.kanban-column').forEach(col => col.classList.remove('drag-over'));
                draggedCardId = null;
            }

            function handleColDragStart(e) {
                // Prevent dragging column when focusing inputs/buttons or child cards
                if (e.target.closest('.kanban-card') || e.target.closest('button') || e.target.closest('input') || e.target.closest('textarea')) {
                    e.preventDefault();
                    return;
                }
                draggedColId = this.getAttribute('data-id');
                this.classList.add('col-dragging');
                e.dataTransfer.setData('text/col-id', draggedColId);
                e.dataTransfer.effectAllowed = 'move';
            }

            function handleColDragEnd(e) {
                this.classList.remove('col-dragging');
                document.querySelectorAll('.kanban-column').forEach(col => {
                    col.classList.remove('col-drag-over');
                    col.classList.remove('drag-over');
                });
                draggedColId = null;
            }

            function handleDragOver(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                return false;
            }

            function handleDragEnter(e) {
                if (draggedCardId) {
                    this.classList.add('drag-over');
                } else if (draggedColId) {
                    if (this.getAttribute('data-id') !== draggedColId) {
                        this.classList.add('col-drag-over');
                    }
                }
            }

            function handleDragLeave(e) {
                this.classList.remove('drag-over');
                this.classList.remove('col-drag-over');
            }

            function handleDrop(e) {
                e.stopPropagation();
                e.preventDefault();
                this.classList.remove('drag-over');
                this.classList.remove('col-drag-over');

                const targetColId = this.getAttribute('data-id');

                if (draggedCardId) {
                    if (targetColId === sourceColumnId) return;

                    let cardToMove = null;
                    const sourceCol = boardData.columns.find(col => col.id === sourceColumnId);
                    const targetCol = boardData.columns.find(col => col.id === targetColId);

                    if (sourceCol && targetCol) {
                        const cardIndex = sourceCol.cards.findIndex(card => card.id === draggedCardId);
                        if (cardIndex !== -1) {
                            cardToMove = sourceCol.cards.splice(cardIndex, 1)[0];
                            cardToMove.date = new Date().toISOString().split('T')[0]; // update date to today on drop
                            targetCol.cards.push(cardToMove);

                            // Re-render and auto-save
                            renderKanbanBoard();
                            saveKanbanBoardState();
                        }
                    }
                    draggedCardId = null;
                } else if (draggedColId) {
                    if (targetColId === draggedColId) return;

                    const fromIndex = boardData.columns.findIndex(col => col.id === draggedColId);
                    const toIndex = boardData.columns.findIndex(col => col.id === targetColId);

                    if (fromIndex !== -1 && toIndex !== -1) {
                        const [movedCol] = boardData.columns.splice(fromIndex, 1);
                        boardData.columns.splice(toIndex, 0, movedCol);

                        // Re-render and auto-save
                        renderKanbanBoard();
                        saveKanbanBoardState();
                    }
                    draggedColId = null;
                }
            }

            // Modal Handlers
            const modalOverlay = document.getElementById('kanban-modal-overlay');
            const modalTitle = document.getElementById('kanban-modal-title');
            const inputId = document.getElementById('kanban-card-id');
            const inputColId = document.getElementById('kanban-column-id');
            const inputTitle = document.getElementById('kanban-input-title');
            const inputDesc = document.getElementById('kanban-input-desc');

            function openAddCardModal(columnId) {
                modalTitle.textContent = 'Tambah Kartu Baru';
                inputId.value = '';
                inputColId.value = columnId;
                inputTitle.value = '';
                inputDesc.value = '';
                modalOverlay.style.display = 'flex';
                inputTitle.focus();
            }

            function openEditCardModal(columnId, cardId) {
                const column = boardData.columns.find(col => col.id === columnId);
                if (!column) return;
                const card = column.cards.find(c => c.id === cardId);
                if (!card) return;

                modalTitle.textContent = 'Sunting Kartu Revisi';
                inputId.value = card.id;
                inputColId.value = columnId;
                inputTitle.value = card.title;
                inputDesc.value = card.desc || '';
                modalOverlay.style.display = 'flex';
                inputTitle.focus();
            }

            function closeKanbanModal() {
                modalOverlay.style.display = 'none';
            }

            // Save Card (Add or Edit)
            function saveKanbanCard() {
                const cardId = inputId.value;
                const colId = inputColId.value;
                const title = inputTitle.value.trim();
                const desc = inputDesc.value.trim();

                if (!title) {
                    showToast('Judul kartu tidak boleh kosong!', true);
                    return;
                }

                const column = boardData.columns.find(col => col.id === colId);
                if (!column) return;

                if (cardId) {
                    // Edit existing card
                    const card = column.cards.find(c => c.id === cardId);
                    if (card) {
                        card.title = title;
                        card.desc = desc;
                        card.date = new Date().toISOString().split('T')[0];
                    }
                } else {
                    // Add new card
                    const newCard = {
                        id: 'card-' + Date.now(),
                        title: title,
                        desc: desc,
                        date: new Date().toISOString().split('T')[0]
                    };
                    column.cards.push(newCard);
                }

                closeKanbanModal();
                renderKanbanBoard();
                saveKanbanBoardState();
            }

            // Delete Card
            function deleteKanbanCard(columnId, cardId) {
                if (!confirm('Apakah Anda yakin ingin menghapus kartu revisi ini?')) return;

                const column = boardData.columns.find(col => col.id === columnId);
                if (!column) return;

                const cardIndex = column.cards.findIndex(c => c.id === cardId);
                if (cardIndex !== -1) {
                    column.cards.splice(cardIndex, 1);
                    renderKanbanBoard();
                    saveKanbanBoardState();
                }
            }

            // Kanban Column Handlers (Add / Delete Columns via Global Modal)
            function openAddColumnModalGlobal() {
                document.getElementById('column-modal-overlay').classList.add('show');
                document.getElementById('new-column-name').focus();
            }

            function closeAddColumnModal() {
                document.getElementById('column-modal-overlay').classList.remove('show');
                document.getElementById('new-column-name').value = '';
            }

            function submitAddColumnGlobal() {
                const nameInput = document.getElementById('new-column-name');
                const title = nameInput.value.trim();

                if (!title) {
                    showToast('Nama kolom tidak boleh kosong!', true);
                    return;
                }

                const colId = 'col-' + Date.now();
                const newColumn = {
                    id: colId,
                    title: title,
                    cards: []
                };

                boardData.columns.push(newColumn);
                renderKanbanBoard();
                saveKanbanBoardState();
                closeAddColumnModal();
                showToast(`Kolom "${title}" berhasil ditambahkan!`, false);
            }

            function deleteKanbanColumn(columnId, columnTitle) {
                if (!confirm(`Apakah Anda yakin ingin menghapus kolom "${columnTitle}"? Semua kartu di dalamnya juga akan terhapus secara permanen.`)) return;
                const colIndex = boardData.columns.findIndex(col => col.id === columnId);
                if (colIndex !== -1) {
                    boardData.columns.splice(colIndex, 1);
                    renderKanbanBoard();
                    saveKanbanBoardState();
                }
            }

            // Save board state to backend file storage (board.json)
            function saveKanbanBoardState() {
                const statusBadge = document.getElementById('status-badge');
                if (statusBadge) {
                    statusBadge.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...';
                    statusBadge.className = 'status-indicator saving';
                }

                fetch('{{ route("dev-docs.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        doc: 'board.json',
                        content: JSON.stringify(boardData, null, 4)
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (statusBadge) {
                                statusBadge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Tersimpan';
                                statusBadge.className = 'status-indicator';
                            }
                            showToast('Papan Kanban berhasil disimpan!', false);
                        } else {
                            showToast(data.message || 'Gagal menyimpan papan', true);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('Gagal terhubung ke server', true);
                    });
            }

            // Auto render on page load
            document.addEventListener('DOMContentLoaded', () => {
                renderKanbanBoard();
            });
        @endif
    </script>
</body>

</html>