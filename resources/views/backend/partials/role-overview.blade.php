<style>
    /* =========================================================
       PREMIUM TRANSPORT OPERATIONS DASHBOARD
    ========================================================= */

    .dashboard-header {
        margin-bottom: 5px;
    }

    .dashboard-header h2 {
        color: #172033;
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    .dashboard-header p {
        font-size: 13px;
        color: #94a3b8;
    }


    /* =========================================================
       PREMIUM STAT CARD
    ========================================================= */

    .premium-stat-card {
        position: relative;
        overflow: hidden;

        min-height: 185px;

        border: 1px solid #e9edf5;
        border-radius: 16px;

        background: #ffffff;

        box-shadow:
            0 6px 24px rgba(15, 23, 42, 0.06);

        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease,
            border-color 0.3s ease;
    }


    .premium-stat-card:hover {
        transform: translateY(-5px);

        border-color: #dfe5ef;

        box-shadow:
            0 16px 40px rgba(15, 23, 42, 0.12);
    }


    /* Left Accent */

    .premium-stat-card::before {
        content: "";

        position: absolute;

        top: 0;
        left: 0;

        width: 5px;
        height: 100%;

        background: var(--card-accent);

        border-radius: 16px 0 0 16px;
    }


    /* Decorative Circle */

    .premium-stat-card::after {
        content: "";

        position: absolute;

        width: 150px;
        height: 150px;

        right: -65px;
        top: -65px;

        border-radius: 50%;

        background: var(--card-soft);

        opacity: 0.75;

        transition:
            transform 0.4s ease,
            opacity 0.4s ease;
    }


    .premium-stat-card:hover::after {
        transform: scale(1.15);
        opacity: 1;
    }


    /* =========================================================
       CARD BODY
    ========================================================= */

    .premium-stat-body {
        position: relative;

        z-index: 2;

        padding: 22px;
    }


    .premium-stat-top {
        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 15px;
    }


    .premium-stat-info {
        min-width: 0;

        flex: 1;
    }


    /* =========================================================
       LABEL
    ========================================================= */

    .premium-stat-label {
        margin-bottom: 8px;

        color: #64748b;

        font-size: 13px;

        font-weight: 600;

        letter-spacing: 0.2px;

        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;
    }


    /* =========================================================
       VALUE
    ========================================================= */

    .premium-stat-value {
        margin: 0;

        color: #172033;

        font-size: 30px;

        line-height: 1;

        font-weight: 800;

        letter-spacing: -0.7px;
    }


    /* =========================================================
       SUBTITLE
    ========================================================= */

    .premium-stat-subtitle {
        margin-top: 9px;

        color: #94a3b8;

        font-size: 12px;

        font-weight: 500;

        line-height: 1.4;
    }


    /* =========================================================
       ICON
    ========================================================= */

    .dashboard-business-icon {
        display: block;
        width: 24px;
        height: 24px;
        flex: 0 0 24px;
        color: currentColor;
    }

    .dashboard-section-marker .dashboard-business-icon { width: 22px; height: 22px; }
    .dashboard-quick-icon .dashboard-business-icon, .dashboard-priority-icon .dashboard-business-icon { width: 20px; height: 20px; }
    .dashboard-summary-icon .dashboard-business-icon { width: 22px; height: 22px; }

    .premium-stat-icon {
        position: relative;

        z-index: 3;

        display: flex;

        align-items: center;

        justify-content: center;

        width: 52px;
        height: 52px;

        flex: 0 0 52px;

        border-radius: 14px;

        background: var(--card-soft);

        color: var(--card-accent);

        font-size: 22px;

        box-shadow:
            0 5px 15px rgba(15, 23, 42, 0.06);

        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease;
    }


    .premium-stat-card:hover .premium-stat-icon {
        transform: scale(1.08) rotate(-3deg);

        box-shadow:
            0 8px 20px rgba(15, 23, 42, 0.10);
    }


    /* =========================================================
       FOOTER
    ========================================================= */

    .premium-stat-footer {
        position: relative;

        z-index: 3;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 10px;

        margin-top: 20px;

        padding-top: 13px;

        border-top: 1px solid #f1f3f7;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .premium-stat-status {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        color: var(--card-accent);

        font-size: 11px;

        font-weight: 700;
    }


    .premium-stat-status .status-dot {
        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: currentColor;

        box-shadow:
            0 0 0 3px var(--card-soft);
    }


    /* =========================================================
       LINK
    ========================================================= */

    .premium-stat-link {
        color: #94a3b8;

        font-size: 12px;

        font-weight: 600;

        text-decoration: none;

        white-space: nowrap;

        transition: all 0.2s ease;
    }


    .premium-stat-link:hover {
        color: var(--card-accent);

        text-decoration: none;
    }


    /* =========================================================
       ROLE BADGE
    ========================================================= */

    .dashboard-role-badge {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        padding: 8px 14px;

        border-radius: 30px;

        background: #f8fafc;

        border: 1px solid #e5e7eb;

        color: #475569;

        font-size: 12px;

        font-weight: 700;

        text-transform: capitalize;

        box-shadow:
            0 3px 10px rgba(15, 23, 42, 0.04);
    }


    .role-dot {
        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #16a34a;

        box-shadow:
            0 0 0 4px #dcfce7;
    }


    /* =========================================================
       CARD COLORS
    ========================================================= */

    .stat-blue {
        --card-accent: #2563eb;
        --card-soft: #eff6ff;
    }

    .stat-green {
        --card-accent: #16a34a;
        --card-soft: #f0fdf4;
    }

    .stat-orange {
        --card-accent: #ea580c;
        --card-soft: #fff7ed;
    }

    .stat-purple {
        --card-accent: #7c3aed;
        --card-soft: #f5f3ff;
    }

    .stat-cyan {
        --card-accent: #0891b2;
        --card-soft: #ecfeff;
    }

    .stat-red {
        --card-accent: #dc2626;
        --card-soft: #fef2f2;
    }

    .stat-indigo {
        --card-accent: #4f46e5;
        --card-soft: #eef2ff;
    }

    .stat-teal {
        --card-accent: #0f766e;
        --card-soft: #f0fdfa;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {

        .premium-stat-body {
            padding: 20px;
        }

        .premium-stat-value {
            font-size: 28px;
        }
    }


    @media (max-width: 767px) {

        .dashboard-role-badge {
            margin-top: 12px;
        }

        .premium-stat-body {
            padding: 18px;
        }

        .premium-stat-value {
            font-size: 26px;
        }

        .premium-stat-icon {
            width: 46px;
            height: 46px;

            flex-basis: 46px;

            font-size: 19px;
        }

        .premium-stat-footer {
            margin-top: 16px;
        }
    }


    @media (max-width: 480px) {

        .premium-stat-top {
            gap: 10px;
        }

        .premium-stat-label {
            font-size: 12px;
        }

        .premium-stat-value {
            font-size: 24px;
        }

        .premium-stat-subtitle {
            font-size: 11px;
        }

        .premium-stat-footer {
            align-items: flex-start;

            flex-direction: column;
        }
    }


    /* =========================================================
       DASHBOARD GROUP HEADINGS AND PAGE META
    ========================================================= */

    .dashboard-section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 12px 0 16px;
        padding: 12px 15px;
        border: 1px solid #e9edf5;
        border-radius: 12px;
        background: linear-gradient(100deg, #f8fafc 0%, #ffffff 100%);
    }

    .dashboard-section-heading h3 {
        margin: 0;
        color: #334155;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: .1px;
    }

    .dashboard-section-heading p {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: 11px;
        line-height: 1.4;
    }

    .dashboard-section-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 29px;
        height: 29px;
        padding: 0 8px;
        border-radius: 9px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 11px;
        font-weight: 800;
    }

    .dashboard-page-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin-top: 9px;
        color: #94a3b8;
        font-size: 12px;
    }

    .dashboard-page-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .dashboard-page-meta i {
        color: #64748b;
    }

    .dashboard-meta-separator {
        color: #cbd5e1;
    }

    .dashboard-access-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 0 0 18px;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        line-height: 1.6;
    }

    .dashboard-access-note i {
        margin-top: 2px;
        color: #64748b;
    }

    .premium-stat-card {
        height: 100%;
    }

    .dashboard-no-route {
        color: #94a3b8;
        font-size: 11px;
        font-weight: 600;
        white-space: normal;
        text-align: right;
    }

    @media (max-width: 767px) {
        .dashboard-section-heading {
            align-items: flex-start;
        }

        .dashboard-section-count {
            flex: 0 0 auto;
        }

        .dashboard-page-meta {
            gap: 7px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .premium-stat-card,
        .premium-stat-card::after,
        .premium-stat-icon,
        .premium-stat-link {
            transition: none !important;
        }

        .premium-stat-card:hover {
            transform: none;
        }
    }



    /* =========================================================
       CLIENT-FRIENDLY DASHBOARD EXPERIENCE
       Workflow navigation, live search, accessible focus states
    ========================================================= */

    .dashboard-welcome-panel {
        position: relative;
        margin: 0 0 20px;
        padding: 24px 26px;
        overflow: hidden;
        border: 1px solid #e7edf5;
        border-radius: 18px;
        background: linear-gradient(125deg, #ffffff 0%, #f8fbff 72%, #f2f7ff 100%);
        box-shadow: 0 8px 28px rgba(15, 23, 42, .045);
    }

    .dashboard-welcome-panel::after {
        content: "";
        position: absolute;
        top: -65px;
        right: -35px;
        width: 190px;
        height: 190px;
        border: 26px solid rgba(37, 99, 235, .045);
        border-radius: 50%;
        pointer-events: none;
    }

    .dashboard-welcome-main,
    .dashboard-welcome-side {
        position: relative;
        z-index: 1;
    }

    .dashboard-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 8px;
        color: #2563eb;
        font-size: 10px;
        line-height: 1.3;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.15px;
    }

    .dashboard-header h2 {
        margin-bottom: 7px;
        color: #162238;
        font-size: clamp(22px, 2vw, 29px);
        font-weight: 800;
        letter-spacing: -.65px;
        line-height: 1.2;
    }

    .dashboard-header p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .dashboard-role-badge {
        background: #ffffff;
        border-color: #dfe7f2;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .04);
    }

    .dashboard-page-meta {
        margin-top: 13px;
        gap: 8px 13px;
        color: #64748b;
    }

    .dashboard-page-meta span {
        white-space: nowrap;
    }

    .dashboard-summary-strip {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin: 0 0 20px;
    }

    .dashboard-summary-item {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        padding: 15px 17px;
        border: 1px solid #e8edf5;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .025);
    }

    .dashboard-summary-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 41px;
        height: 41px;
        flex: 0 0 41px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 17px;
    }

    .dashboard-summary-item:nth-child(2) .dashboard-summary-icon {
        color: #0f766e;
        background: #f0fdfa;
    }

    .dashboard-summary-item:nth-child(3) .dashboard-summary-icon {
        color: #7c3aed;
        background: #f5f3ff;
    }

    .dashboard-summary-copy {
        min-width: 0;
    }

    .dashboard-summary-value {
        display: block;
        margin: 0 0 3px;
        color: #172033;
        font-size: 21px;
        font-weight: 800;
        letter-spacing: -.45px;
        line-height: 1.1;
        font-variant-numeric: tabular-nums;
    }

    .dashboard-summary-label {
        display: block;
        color: #64748b;
        font-size: 11px;
        line-height: 1.4;
    }

    .dashboard-sr-only {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }

    .dashboard-controls {
        margin-bottom: 18px;
        padding: 16px;
        border: 1px solid #e8edf5;
        border-radius: 15px;
        background: #ffffff;
        box-shadow: 0 4px 16px rgba(15, 23, 42, .025);
    }

    .dashboard-controls-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 12px;
    }

    .dashboard-controls-title {
        margin: 0;
        color: #273449;
        font-size: 13px;
        font-weight: 800;
    }

    .dashboard-controls-hint {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: 11px;
        line-height: 1.5;
    }

    .dashboard-search-wrap {
        position: relative;
        width: min(100%, 440px);
    }

    .dashboard-search-wrap > i {
        position: absolute;
        top: 50%;
        left: 14px;
        color: #94a3b8;
        font-size: 14px;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .dashboard-search-input {
        display: block;
        width: 100%;
        height: 43px;
        padding: 10px 42px 10px 39px;
        border: 1px solid #dfe7f1;
        border-radius: 11px;
        background: #f8fafc;
        color: #172033;
        font-size: 12px;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .dashboard-search-input::placeholder {
        color: #94a3b8;
    }

    .dashboard-search-input:focus {
        border-color: #93c5fd;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
    }

    .dashboard-search-clear {
        position: absolute;
        top: 50%;
        right: 9px;
        display: none;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border: 0;
        border-radius: 7px;
        background: transparent;
        color: #64748b;
        transform: translateY(-50%);
        cursor: pointer;
    }

    .dashboard-search-clear:hover {
        background: #eaf0f8;
        color: #172033;
    }

    .dashboard-filter-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .dashboard-filter-label {
        margin-right: 3px;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
    }

    .dashboard-filter-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 33px;
        padding: 7px 11px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #ffffff;
        color: #64748b;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        line-height: 1.2;
        cursor: pointer;
        transition: color .2s ease, background .2s ease, border-color .2s ease;
    }

    .dashboard-filter-button:hover {
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .dashboard-filter-button.is-active,
    .dashboard-filter-button[aria-pressed="true"] {
        border-color: #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .dashboard-filter-button:focus-visible,
    .dashboard-search-clear:focus-visible,
    .dashboard-section-summary:focus-visible,
    .premium-stat-link:focus-visible {
        outline: 3px solid rgba(59, 130, 246, .35);
        outline-offset: 3px;
    }

    .dashboard-results-count {
        margin-left: auto;
        color: #64748b;
        font-size: 11px;
        white-space: nowrap;
    }

    .dashboard-results-count strong {
        color: #172033;
        font-weight: 800;
    }

    .dashboard-section {
        margin-bottom: 17px;
        overflow: hidden;
        border: 1px solid #e4eaf3;
        border-radius: 15px;
        background: #ffffff;
        box-shadow: 0 5px 18px rgba(15, 23, 42, .025);
    }

    .dashboard-section[hidden],
    .dashboard-stat-card[hidden],
    .dashboard-section-empty[hidden],
    .dashboard-no-results[hidden] {
        display: none !important;
    }

    .dashboard-section-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 18px;
        border-bottom: 1px solid transparent;
        background: #fbfcfe;
        cursor: default;
        list-style: none;
        transition: background .2s ease;
    }

    .dashboard-section-summary::-webkit-details-marker {
        display: none;
    }

    .dashboard-section-summary::marker {
        content: "";
    }

    .dashboard-section-summary:hover {
        background: #f6f9fd;
    }

    .dashboard-section-heading {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
        margin: 0;
        padding: 0;
        border: 0;
        background: transparent;
    }

    .dashboard-section-marker {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        border-radius: 11px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 15px;
    }

    .dashboard-section-heading-copy {
        display: block;
        min-width: 0;
    }

    .dashboard-section-heading h3 {
        color: #1e293b;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0;
        line-height: 1.4;
    }

    .dashboard-section-heading p {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: 11px;
        line-height: 1.5;
    }

    .dashboard-section-trailing {
        display: flex;
        align-items: center;
        flex: 0 0 auto;
        gap: 10px;
    }

    .dashboard-section-count {
        min-width: 34px;
        height: 30px;
        padding: 0 9px;
        border: 1px solid #dbeafe;
        border-radius: 9px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 800;
    }

    .dashboard-section-content {
        padding: 17px 14px 1px;
    }

    .dashboard-section-content .premium-stat-card {
        border-radius: 13px;
        box-shadow: 0 3px 13px rgba(15, 23, 42, .035);
    }

    .dashboard-section-content .premium-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, .085);
    }

    .premium-stat-card {
        min-height: 174px;
        border-color: #e8edf5;
    }

    .premium-stat-body {
        display: flex;
        min-height: inherit;
        flex-direction: column;
        padding: 18px;
    }

    .premium-stat-top {
        flex: 1;
        gap: 12px;
    }

    .premium-stat-label {
        min-height: 32px;
        margin-bottom: 5px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.45;
        letter-spacing: 0;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .premium-stat-value {
        color: #172033;
        font-size: clamp(23px, 2vw, 29px);
        font-variant-numeric: tabular-nums;
        line-height: 1.15;
        overflow-wrap: anywhere;
    }

    .premium-stat-subtitle {
        margin-top: 7px;
        color: #8492a6;
        font-size: 11px;
        line-height: 1.55;
    }

    .premium-stat-icon {
        width: 44px;
        height: 44px;
        flex-basis: 44px;
        border-radius: 12px;
        box-shadow: none;
        font-size: 19px;
    }

    .premium-stat-footer {
        margin-top: 14px;
        padding-top: 11px;
    }

    .premium-stat-status {
        padding: 5px 8px;
        border-radius: 7px;
        background: var(--card-soft);
        font-size: 10px;
        line-height: 1.3;
    }

    .premium-stat-status .status-dot {
        width: 6px;
        height: 6px;
        box-shadow: none;
    }

    .premium-stat-link {
        color: var(--card-accent);
        font-size: 11px;
        font-weight: 800;
    }

    .dashboard-no-route {
        max-width: 48%;
        color: #a0aabb;
        font-size: 10px;
        font-weight: 600;
        line-height: 1.4;
    }

    .dashboard-no-results {
        margin: 0 0 20px;
        padding: 32px 20px;
        border: 1px dashed #cbd5e1;
        border-radius: 15px;
        background: #f8fafc;
        text-align: center;
    }

    .dashboard-no-results-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        border-radius: 14px;
        background: #eaf2ff;
        color: #2563eb;
        font-size: 18px;
    }

    .dashboard-no-results h3 {
        margin: 0 0 6px;
        color: #1e293b;
        font-size: 15px;
        font-weight: 800;
    }

    .dashboard-no-results p {
        margin: 0 0 15px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.6;
    }

    .dashboard-reset-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 13px;
        border: 1px solid #bfdbfe;
        border-radius: 9px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
    }

    .dashboard-access-note {
        margin-bottom: 18px;
        border-color: #e5edf8;
        background: #f8fbff;
        color: #64748b;
    }

    .dashboard-access-note strong {
        color: #334155;
    }

    @media (max-width: 767px) {
        .dashboard-welcome-panel {
            padding: 20px 18px;
            border-radius: 15px;
        }

        .dashboard-summary-strip {
            grid-template-columns: 1fr;
            gap: 9px;
        }

        .dashboard-summary-item {
            padding: 12px 14px;
        }

        .dashboard-summary-icon {
            width: 37px;
            height: 37px;
            flex-basis: 37px;
        }

        .dashboard-summary-value {
            font-size: 19px;
        }

        .dashboard-controls {
            padding: 13px;
        }

        .dashboard-controls-top {
            align-items: stretch;
            flex-direction: column;
        }

        .dashboard-search-wrap {
            width: 100%;
        }

        .dashboard-results-count {
            width: 100%;
            margin-left: 0;
            padding-top: 4px;
        }

        .dashboard-section-summary {
            padding: 13px 12px;
        }

        .dashboard-section-heading {
            gap: 9px;
        }

        .dashboard-section-marker {
            width: 34px;
            height: 34px;
            flex-basis: 34px;
            font-size: 13px;
        }

        .dashboard-section-heading h3 {
            font-size: 12px;
        }

        .dashboard-section-heading p {
            font-size: 10px;
        }

        .dashboard-section-content {
            padding: 12px 8px 1px;
        }

        .premium-stat-body {
            padding: 15px;
        }

        .premium-stat-icon {
            width: 39px;
            height: 39px;
            flex-basis: 39px;
            font-size: 17px;
        }

        .premium-stat-footer {
            align-items: center;
            flex-direction: row;
            gap: 7px;
        }
    }

    @media (max-width: 420px) {
        .dashboard-role-badge {
            margin-top: 14px;
        }

        .dashboard-page-meta {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-meta-separator {
            display: none !important;
        }

        .dashboard-filter-row {
            gap: 7px;
        }

        .dashboard-filter-label {
            width: 100%;
            margin-bottom: 1px;
        }

        .dashboard-filter-button {
            padding: 7px 9px;
            font-size: 10px;
        }

        .dashboard-section-count {
            min-width: 29px;
            height: 27px;
            padding: 0 6px;
        }

        .dashboard-section-trailing {
            gap: 7px;
        }

        .premium-stat-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-no-route {
            max-width: 100%;
            text-align: left;
        }
    }



    /* =========================================================
       BUSINESS ACTION CENTER, DUTY BOARD AND REPORT WIDGETS
    ========================================================= */
    .dashboard-widget-grid { display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr); gap: 18px; margin: 0 0 20px; }
    .dashboard-widget-panel { background: #fff; border: 1px solid #e7ecf3; border-radius: 18px; box-shadow: 0 8px 24px rgba(15,23,42,.045); overflow: hidden; min-width: 0; }
    .dashboard-widget-panel-full { margin-bottom: 20px; }
    .dashboard-widget-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 20px 22px 15px; border-bottom: 1px solid #eef2f7; }
    .dashboard-widget-heading { display: flex; gap: 11px; align-items: flex-start; min-width: 0; }
    .dashboard-widget-heading-icon { width: 40px; height: 40px; flex: 0 0 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; background: #eff6ff; color: #2563eb; font-size: 17px; }
    .dashboard-widget-heading-icon.is-warning { background: #fff7ed; color: #c2410c; }
    .dashboard-widget-heading-icon.is-success { background: #ecfdf5; color: #047857; }
    .dashboard-widget-heading-icon.is-purple { background: #f5f3ff; color: #6d28d9; }
    .dashboard-widget-title { color: #172033; font-size: 16px; font-weight: 800; line-height: 1.35; margin: 0; }
    .dashboard-widget-description { color: #718096; font-size: 12px; line-height: 1.5; margin: 4px 0 0; }
    .dashboard-widget-body { padding: 17px 20px 20px; }
    .dashboard-widget-total { flex: 0 0 auto; border-radius: 999px; padding: 5px 10px; font-size: 11px; font-weight: 800; color: #475569; background: #f1f5f9; }
    .dashboard-priority-list { display: flex; flex-direction: column; gap: 9px; }
    .dashboard-priority-item { display: flex; align-items: center; gap: 11px; border: 1px solid #edf1f6; border-radius: 12px; padding: 11px 12px; min-width: 0; transition: border-color .2s, background .2s; }
    .dashboard-priority-item:hover { background: #fbfdff; border-color: #dce5f0; }
    .dashboard-priority-icon { display: inline-flex; align-items: center; justify-content: center; flex: 0 0 34px; width: 34px; height: 34px; border-radius: 10px; background: #fff7ed; color: #c2410c; }
    .dashboard-priority-item.is-danger .dashboard-priority-icon { background: #fef2f2; color: #b91c1c; }
    .dashboard-priority-copy { min-width: 0; flex: 1; }
    .dashboard-priority-copy strong { display: block; color: #334155; font-size: 12px; font-weight: 700; line-height: 1.45; }
    .dashboard-priority-copy span { display: block; color: #94a3b8; font-size: 11px; margin-top: 2px; }
    .dashboard-priority-count { min-width: 32px; text-align: center; padding: 5px 8px; border-radius: 8px; background: #fff7ed; color: #9a3412; font-size: 12px; font-weight: 800; }
    .dashboard-priority-item.is-danger .dashboard-priority-count { color: #991b1b; background: #fef2f2; }
    .dashboard-priority-link { color: inherit; text-decoration: none !important; }
    .dashboard-priority-link .dashboard-priority-arrow { color: #94a3b8; flex: 0 0 auto; }
    .dashboard-empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 24px 16px; border: 1px dashed #dbe4ef; border-radius: 13px; color: #64748b; background: #fbfdff; }
    .dashboard-empty-state > i { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #ecfdf5; color: #059669; font-size: 18px; margin-bottom: 10px; }
    .dashboard-empty-state strong { color: #334155; font-size: 13px; }
    .dashboard-empty-state span { max-width: 360px; color: #8492a6; font-size: 12px; margin-top: 5px; line-height: 1.5; }
    .dashboard-empty-state a { margin-top: 10px; color: #2563eb; font-size: 12px; font-weight: 700; }
    .dashboard-quick-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
    .dashboard-quick-action { display: flex; align-items: center; gap: 10px; min-width: 0; padding: 12px; border: 1px solid #e9eef5; border-radius: 12px; background: #fff; color: #334155; text-decoration: none !important; transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease; }
    .dashboard-quick-action:hover { transform: translateY(-2px); border-color: #cbd9eb; box-shadow: 0 7px 18px rgba(15,23,42,.06); color: #1d4ed8; }
    .dashboard-quick-icon { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; flex: 0 0 36px; border-radius: 10px; background: #eff6ff; color: #2563eb; font-size: 15px; }
    .dashboard-quick-copy { min-width: 0; flex: 1; }
    .dashboard-quick-copy strong { display: block; font-size: 12px; line-height: 1.4; }
    .dashboard-quick-copy span { display: block; color: #94a3b8; font-size: 10px; line-height: 1.4; margin-top: 3px; }
    .dashboard-widget-toolbar { display: flex; flex-wrap: wrap; align-items: end; justify-content: space-between; gap: 14px; }
    .dashboard-date-filter { display: flex; flex-wrap: wrap; align-items: end; gap: 9px; }
    .dashboard-date-field { display: flex; flex-direction: column; gap: 4px; color: #64748b; font-size: 10px; font-weight: 700; }
    .dashboard-date-field select, .dashboard-date-field input { min-height: 36px; border: 1px solid #d9e2ed; border-radius: 9px; padding: 7px 10px; font-size: 12px; color: #334155; background: #fff; max-width: 100%; }
    .dashboard-small-button { border: 1px solid #dbe4ef; border-radius: 9px; background: #fff; color: #334155; font-size: 11px; font-weight: 700; padding: 9px 12px; cursor: pointer; }
    .dashboard-small-button:hover { border-color: #93c5fd; color: #1d4ed8; }
    .dashboard-small-button.is-primary { background: #2563eb; border-color: #2563eb; color: #fff; }
    .dashboard-widget-action-row { display: flex; flex-wrap: wrap; gap: 8px; }
    .dashboard-data-note { display: flex; align-items: flex-start; gap: 8px; color: #77869a; font-size: 11px; line-height: 1.5; margin-top: 12px; }
    .dashboard-data-note i { color: #64748b; margin-top: 2px; }
    .dashboard-chart-grid { display: grid; grid-template-columns: minmax(0, 1.4fr) minmax(250px, .8fr); gap: 18px; }
    .dashboard-chart-grid-single { grid-template-columns: minmax(0, 1fr) !important; }
    .dashboard-chart-card { min-width: 0; border: 1px solid #edf1f6; border-radius: 14px; padding: 15px; }
    .dashboard-chart-title { margin: 0; color: #334155; font-size: 13px; font-weight: 800; }
    .dashboard-chart-subtitle { margin: 4px 0 12px; color: #94a3b8; font-size: 10px; }
    .dashboard-chart-legend { display: flex; flex-wrap: wrap; gap: 12px; color: #64748b; font-size: 10px; margin-bottom: 10px; }
    .dashboard-legend-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 5px; }
    .dashboard-legend-blue { background: #3b82f6; } .dashboard-legend-green { background: #10b981; }
    .dashboard-chart-scroll { overflow-x: auto; padding-bottom: 5px; }
    .dashboard-chart-bars { display: flex; align-items: stretch; gap: 5px; min-height: 145px; min-width: max-content; border-bottom: 1px solid #e7edf5; padding: 0 2px; }
    .dashboard-chart-column { display: flex; flex-direction: column; align-items: center; justify-content: end; width: 28px; height: 145px; flex: 0 0 28px; gap: 5px; }
    .dashboard-chart-bar-pair { display: flex; align-items: end; justify-content: center; gap: 3px; height: 115px; width: 100%; }
    .dashboard-chart-bar { display: block; width: 9px; min-height: 0; border-radius: 4px 4px 0 0; transition: opacity .15s; }
    .dashboard-chart-bar:hover { opacity: .7; }
    .dashboard-chart-bar.is-blue { background: #3b82f6; } .dashboard-chart-bar.is-green { background: #10b981; }
    .dashboard-chart-date { color: #94a3b8; font-size: 9px; line-height: 1.1; white-space: nowrap; }
    .dashboard-bar-list { display: flex; flex-direction: column; gap: 12px; }
    .dashboard-bar-row { display: grid; grid-template-columns: 63px minmax(0,1fr) auto; align-items: center; gap: 8px; }
    .dashboard-bar-label { color: #64748b; font-size: 10px; white-space: nowrap; }
    .dashboard-bar-track { height: 9px; background: #eef2f7; border-radius: 999px; overflow: hidden; }
    .dashboard-bar-fill { height: 100%; border-radius: inherit; background: #8b5cf6; }
    .dashboard-bar-value { color: #334155; font-size: 10px; font-weight: 700; white-space: nowrap; }
    .dashboard-fleet-list { display: flex; flex-direction: column; gap: 13px; }
    .dashboard-fleet-line { display: flex; justify-content: space-between; gap: 12px; color: #64748b; font-size: 11px; margin-bottom: 6px; }
    .dashboard-fleet-line strong { color: #334155; font-weight: 800; }
    .dashboard-fleet-track { width: 100%; height: 8px; border-radius: 999px; background: #edf2f7; overflow: hidden; }
    .dashboard-fleet-fill { height: 100%; border-radius: inherit; background: #10b981; }
    .dashboard-fleet-fill.is-amber { background: #f59e0b; } .dashboard-fleet-fill.is-slate { background: #94a3b8; }
    .dashboard-duty-table-wrap { overflow-x: auto; }
    .dashboard-duty-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 11px; min-width: 650px; }
    .dashboard-duty-table th { padding: 10px 11px; text-align: left; background: #f8fafc; color: #64748b; font-size: 10px; text-transform: uppercase; letter-spacing: .04em; border-bottom: 1px solid #e7edf5; }
    .dashboard-duty-table td { padding: 12px 11px; color: #475569; border-bottom: 1px solid #f0f3f8; vertical-align: middle; }
    .dashboard-duty-table tbody tr:last-child td { border-bottom: 0; }
    .dashboard-duty-code { color: #1e293b; font-weight: 800; white-space: nowrap; }
    .dashboard-duty-secondary { color: #94a3b8; font-size: 10px; display: block; margin-top: 3px; }
    .dashboard-status-pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 999px; padding: 5px 8px; font-size: 10px; font-weight: 800; text-transform: capitalize; white-space: nowrap; background: #fef3c7; color: #92400e; }
    .dashboard-status-pill.is-complete { background: #d1fae5; color: #065f46; } .dashboard-status-pill.is-danger { background: #fee2e2; color: #991b1b; } .dashboard-status-pill.is-running { background: #dbeafe; color: #1d4ed8; }
    .dashboard-activity-list { display: flex; flex-direction: column; }
    .dashboard-activity-item { display: flex; gap: 11px; padding: 12px 0; border-bottom: 1px solid #f0f3f8; }
    .dashboard-activity-item:last-child { border-bottom: 0; }
    .dashboard-activity-dot { width: 31px; height: 31px; display: flex; flex: 0 0 31px; justify-content: center; align-items: center; border-radius: 10px; background: #eff6ff; color: #2563eb; }
    .dashboard-activity-copy { min-width: 0; flex: 1; }
    .dashboard-activity-copy strong { display: block; font-size: 11px; line-height: 1.4; color: #334155; }
    .dashboard-activity-copy span { display: block; font-size: 11px; line-height: 1.5; color: #64748b; margin-top: 3px; overflow-wrap: anywhere; }
    .dashboard-activity-copy time { display: block; font-size: 10px; color: #94a3b8; margin-top: 4px; }
    /* Animated business-metrics overview */
    .dashboard-visual-overview { display:grid; grid-template-columns:minmax(230px,.72fr) minmax(0,1.6fr); gap:16px; margin:0 0 18px; }
    .dashboard-visual-card { min-width:0; background:#fff; border:1px solid #e8eef6; border-radius:16px; padding:18px; box-shadow:0 5px 18px rgba(15,23,42,.035); }
    .dashboard-visual-heading { margin:0 0 4px; font-size:14px; font-weight:800; color:#172033; }
    .dashboard-visual-subtitle { margin:0 0 14px; font-size:11px; color:#8996a8; }
    .dashboard-donut-layout { display:flex; align-items:center; justify-content:center; gap:18px; flex-wrap:wrap; }
    .dashboard-donut { width:142px; height:142px; flex:0 0 142px; overflow:visible; }
    .dashboard-donut-track { fill:none; stroke:#edf2f7; stroke-width:12; }
    .dashboard-donut-segment { fill:none; stroke-width:12; stroke-linecap:butt; transform:rotate(-90deg); transform-origin:60px 60px; animation:dashboardDonutReveal 1.15s cubic-bezier(.2,.75,.25,1) both; }
    .dashboard-donut-center { font-size:17px; font-weight:800; fill:#172033; text-anchor:middle; }
    .dashboard-donut-caption { font-size:7px; fill:#8b98aa; text-anchor:middle; }
    .dashboard-donut-legend { display:flex; flex-direction:column; gap:8px; min-width:120px; flex:1; }
    .dashboard-donut-legend-row { display:grid; grid-template-columns:8px minmax(0,1fr) auto; gap:7px; align-items:center; font-size:10px; color:#64748b; }
    .dashboard-donut-swatch { width:8px; height:8px; border-radius:3px; }
    .dashboard-donut-legend-row strong { color:#334155; }
    .dashboard-section-bars { display:flex; flex-direction:column; gap:12px; }
    .dashboard-section-bar-head { display:flex; justify-content:space-between; gap:12px; margin-bottom:5px; font-size:10px; color:#64748b; }
    .dashboard-section-bar-head strong { color:#334155; font-weight:800; }
    .dashboard-section-bar-track { height:8px; overflow:hidden; border-radius:999px; background:#edf2f7; }
    .dashboard-section-bar-fill { height:100%; width:0; border-radius:999px; background:linear-gradient(90deg,#3b82f6,#8b5cf6); animation:dashboardBarReveal .9s cubic-bezier(.2,.75,.25,1) forwards; animation-delay:var(--bar-delay,0ms); }
    .dashboard-section-bar-row { cursor:pointer; padding:2px 0; }
    .dashboard-section-bar-row:hover .dashboard-section-bar-head strong { color:#2563eb; }
    .premium-stat-card { animation:dashboardCardEnter .42s both; animation-delay:calc(var(--card-index, 0) * 18ms); }
    .dashboard-section { animation:dashboardCardEnter .35s both; }
    .dashboard-section-summary { cursor:default; }
    .dashboard-section-content { display:block; }
    .dashboard-section-summary { border-bottom:1px solid #edf2f7; }
    .dashboard-section-summary:focus-visible, .dashboard-section-bar-row:focus-visible { outline:3px solid rgba(59,130,246,.35); outline-offset:3px; }
    @keyframes dashboardBarReveal { from { width:0; } to { width:var(--bar-width,0%); } }
    @keyframes dashboardDonutReveal { from { stroke-dasharray:0 251.2; opacity:.35; } to { opacity:1; } }
    @keyframes dashboardCardEnter { from { opacity:0; transform:translateY(9px); } to { opacity:1; transform:translateY(0); } }
    @media (max-width: 991px) { .dashboard-widget-grid, .dashboard-chart-grid { grid-template-columns: 1fr; } .dashboard-visual-overview { grid-template-columns:1fr; } }
    @media (prefers-reduced-motion: reduce) { .premium-stat-card, .dashboard-section, .dashboard-section-bar-fill, .dashboard-donut-segment { animation:none !important; transition:none !important; } }

    @media (max-width: 767px) {
        .dashboard-widget-grid { gap: 13px; }
        .dashboard-widget-header { padding: 16px 15px 13px; }
        .dashboard-widget-body { padding: 14px; }
        .dashboard-quick-grid { grid-template-columns: 1fr; }
        .dashboard-widget-toolbar { align-items: stretch; }
        .dashboard-date-filter { align-items: stretch; }
        .dashboard-date-field { flex: 1 1 130px; }
        .dashboard-date-field select, .dashboard-date-field input { width: 100%; }
        .dashboard-widget-action-row { width: 100%; }
        .dashboard-small-button { flex: 1 1 auto; }
    }
    @media print {
        .dashboard-controls, .dashboard-widget-action-row, .dashboard-date-filter, .dashboard-search-clear, .dashboard-no-results { display: none !important; }
        .dashboard-widget-panel, .premium-stat-card { box-shadow: none !important; break-inside: avoid; }
        .dashboard-section { break-inside: avoid; }
        .dashboard-section-content { display: block !important; }
        body { background: #fff !important; }
    }



    /* =========================================================
       REPORT FILTERS AND TABLES — aligned with backend report pages
    ========================================================= */
    .dashboard-report-filter-card { margin-bottom: 18px; }
    .dashboard-report-filter-heading { margin: 0 0 18px; color: #2563eb; font-size: 17px; font-weight: 700; }
    .dashboard-report-filter,
    .dashboard-user-filter {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px 18px;
        padding: 20px;
        border: 1px solid #023a85;
        border-radius: 6px;
        background: #fff;
    }
    .dashboard-report-filter-field,
    .dashboard-user-filter-field { display: flex; flex-direction: column; min-width: 0; gap: 7px; margin: 0; }
    .dashboard-report-filter-field label,
    .dashboard-user-filter-field label { margin: 0; color: #334155; font-size: 12px; font-weight: 700; }
    .dashboard-report-filter input:not([type="hidden"]),
    .dashboard-report-filter select,
    .dashboard-user-filter input:not([type="hidden"]),
    .dashboard-user-filter select {
        display: block;
        width: 100%;
        min-width: 0;
        min-height: 38px;
        padding: 8px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background: #fff;
        color: #495057;
        font-size: 12px;
        line-height: 1.5;
        box-shadow: none;
    }
    .dashboard-report-filter input:not([type="hidden"]):focus,
    .dashboard-report-filter select:focus,
    .dashboard-user-filter input:not([type="hidden"]):focus,
    .dashboard-user-filter select:focus { border-color: #80bdff; outline: 0; box-shadow: 0 0 0 3px rgba(0,123,255,.12); }
    .dashboard-report-filter-actions,
    .dashboard-user-filter-actions { display: flex; align-items: end; flex-wrap: wrap; gap: 8px; }
    .dashboard-report-filter-actions .btn,
    .dashboard-user-filter-actions .btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; min-height: 38px; font-size: 12px; white-space: nowrap; }
    .dashboard-report-filter .btn-secondary,
    .dashboard-user-filter .btn-secondary { border: 1px solid #6c757d; }
    .dashboard-user-directory { border: 0; border-radius: 0; overflow: visible; background: transparent; }
    .dashboard-user-directory-panel, .dashboard-user-directory-panel .dashboard-widget-body { min-width: 0; overflow: visible; }
    .dashboard-user-table-wrap { width: 100%; overflow-x: auto; }
    .dashboard-user-table,
    .dashboard-report-table { width: 100%; min-width: 900px; margin-bottom: 0; border-collapse: separate; border-spacing: 0; background: #fff; }
    .dashboard-user-table th,
    .dashboard-report-table th { padding: 12px 14px; text-align: left; vertical-align: middle; background: #fff; border-bottom: 1px solid #e8eef6; color: #64748b; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .055em; white-space: nowrap; }
    .dashboard-user-table td,
    .dashboard-report-table td { padding: 12px 14px; border-bottom: 1px solid #f0f3f8; color: #334155; vertical-align: middle; font-size: 12px; }
    .dashboard-user-table tbody tr:last-child td,
    .dashboard-report-table tbody tr:last-child td { border-bottom: 0; }
    .dashboard-user-table tbody tr,
    .dashboard-report-table tbody tr { transition: background .18s ease; }
    .dashboard-user-table tbody tr:hover,
    .dashboard-report-table tbody tr:hover { background: #f8fbff; }
    .dashboard-report-table-card { overflow: visible; border-radius: 6px; }
    .dashboard-report-table-card > .pd-20 { border-bottom: 1px solid #edf1f6; }
    .dashboard-report-table-title { margin: 0; color: #2563eb; font-size: 17px; font-weight: 700; }
    .dashboard-user-identity { display:flex; gap:10px; align-items:center; min-width:190px; }
    .dashboard-user-avatar { width:34px;height:34px;display:flex;align-items:center;justify-content:center;flex:0 0 34px;border-radius:11px;background:#eff6ff;color:#2563eb;font-weight:800;font-size:12px; }
    .dashboard-user-name { display:block;color:#0f172a;font-weight:800;font-size:12px; }
    .dashboard-user-email { display:block;margin-top:3px;color:#94a3b8;font-size:10px;overflow-wrap:anywhere; }
    .dashboard-user-badge { display:inline-flex;align-items:center;border-radius:30px;padding:5px 8px;font-weight:700;font-size:10px;text-transform:capitalize;background:#f1f5f9;color:#475569;white-space:nowrap; }
    .dashboard-user-badge.is-active { background:#dcfce7;color:#166534; }
    .dashboard-user-badge.is-inactive { background:#fee2e2;color:#991b1b; }
    .dashboard-user-view-activity { display:inline-flex;align-items:center;gap:6px;padding:7px 9px;border:1px solid #bfdbfe;background:#eff6ff;border-radius:4px;color:#1d4ed8;text-decoration:none;font-weight:800;font-size:11px;white-space:nowrap; }
    .dashboard-user-view-activity:hover { background:#dbeafe;text-decoration:none;color:#1e40af; }
    .dashboard-basic-details-button { display:inline-flex; align-items:center; gap:6px; padding:7px 9px; border:1px solid #dbe4ef; border-radius:5px; background:#fff; color:#334155; font-size:11px; font-weight:800; white-space:nowrap; cursor:pointer; }
    .dashboard-basic-details-button:hover, .dashboard-basic-details-button[aria-expanded="true"] { border-color:#93c5fd; background:#eff6ff; color:#1d4ed8; }
    .dashboard-basic-details-button:focus-visible { outline:3px solid rgba(59,130,246,.28); outline-offset:2px; }
    .dashboard-basic-details-row[hidden] { display:none !important; }
    .dashboard-basic-details-row > td { padding:0 !important; background:#f8fbff !important; }
    .dashboard-basic-details-panel { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px 18px; padding:16px 18px; border-top:1px solid #e4edf8; border-bottom:1px solid #e4edf8; }
    .dashboard-basic-details-item { min-width:0; }
    .dashboard-basic-details-label { display:block; margin-bottom:4px; color:#94a3b8; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.04em; }
    .dashboard-basic-details-value { display:block; color:#334155; font-size:12px; font-weight:700; overflow-wrap:anywhere; }
    @media(max-width:767px) { .dashboard-basic-details-panel { grid-template-columns:repeat(2,minmax(0,1fr)); padding:13px; gap:11px; } }
    @media(max-width:420px) { .dashboard-basic-details-panel { grid-template-columns:1fr; } }
    .dashboard-user-pagination { display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:12px 14px;border-top:1px solid #e8eef6;background:#fbfdff;font-size:11px;color:#64748b; }
    .dashboard-user-pagination nav { display:flex; align-items:center; justify-content:flex-end; max-width:100%; }
    .dashboard-user-pagination .pagination { display:flex; align-items:center; flex-wrap:wrap; gap:4px; margin:0; padding:0; list-style:none; }
    .dashboard-user-pagination .page-item { margin:0; }
    .dashboard-user-pagination .page-link { display:inline-flex; align-items:center; justify-content:center; min-width:32px; min-height:32px; padding:6px 10px; border:1px solid #dbe4ef; border-radius:7px; background:#fff; color:#2563eb; font-size:12px; line-height:1.2; text-decoration:none; box-shadow:none; }
    .dashboard-user-pagination .page-link:hover { border-color:#93c5fd; background:#eff6ff; color:#1d4ed8; }
    .dashboard-user-pagination .page-item.active .page-link { border-color:#2563eb; background:#2563eb; color:#fff; }
    .dashboard-user-pagination .page-item.disabled .page-link { border-color:#e5e7eb; background:#f8fafc; color:#9ca3af; pointer-events:none; }
    .dashboard-user-pagination svg { display:inline-block; width:14px !important; height:14px !important; max-width:14px !important; vertical-align:middle; }
    .dashboard-user-pagination p { margin:0; }
    .dashboard-audit-location { display:inline-flex;align-items:center;gap:5px;color:#2563eb;font-size:10px;font-weight:700;text-decoration:none; }
    .dashboard-audit-location:hover { text-decoration:underline; }
    .dashboard-audit-location-meta { display:block; margin-top:4px; color:#94a3b8; font-size:10px; line-height:1.4; }
    .dashboard-own-activity-note { margin-top:12px;font-size:11px;color:#64748b;line-height:1.5; }
    .dashboard-report-empty { padding: 28px 16px !important; text-align: center; color: #64748b !important; }
    @media(max-width:1200px) { .dashboard-report-filter, .dashboard-user-filter { grid-template-columns:repeat(3,minmax(0,1fr)); } }
    @media(max-width:767px) {
        .dashboard-report-filter, .dashboard-user-filter { grid-template-columns:repeat(2,minmax(0,1fr)); padding:14px; gap:13px; }
        .dashboard-report-filter .dashboard-report-search-field,
        .dashboard-user-filter .dashboard-user-search-field { grid-column:1/-1; }
        .dashboard-report-filter-actions, .dashboard-user-filter-actions { grid-column:1/-1; align-items:stretch; }
        .dashboard-report-filter-actions .btn, .dashboard-user-filter-actions .btn { flex:1 1 auto; }
        .dashboard-user-pagination { align-items:flex-start;flex-direction:column; }
        .dashboard-report-table-card > .pd-20 { padding: 15px !important; }
    }
    @media(max-width:420px) {
        .dashboard-report-filter, .dashboard-user-filter { grid-template-columns:1fr; }
        .dashboard-report-filter .dashboard-report-search-field,
        .dashboard-user-filter .dashboard-user-search-field,
        .dashboard-report-filter-actions, .dashboard-user-filter-actions { grid-column:1; }
    }

</style>


@php
    /*
    |--------------------------------------------------------------------------
    | ROLE-AWARE DASHBOARD VIEW MODEL
    |--------------------------------------------------------------------------
    |
    | All model queries belong in DashboardService, not inside Blade. This view
    | renders only the role-safe aggregate data provided by the controller.
    | Cards are grouped by business workflow and route links are resolved only
    | when their named route exists, preventing RouteNotFoundException errors.
    | The admin dashboard includes data from all 21 supplied models; operational,
    | accountant and driver roles receive only the relevant business indicators.
    */

    $user = auth()->user();
    $role = strtolower((string) ($user?->role ?? ''));
    $dashboardStats = isset($dashboardStats) && is_array($dashboardStats) ? $dashboardStats : [];

    /** Resolve the first registered module route without assuming every module has a route. */
    $dashboardRoute = static function (...$candidates) {
        foreach ($candidates as $candidate) {
            if (is_string($candidate) && $candidate !== '' && \Illuminate\Support\Facades\Route::has($candidate)) {
                return route($candidate);
            }
        }

        return '#';
    };

    /** Map dashboard labels to domain-specific SVG icon names, independent of icon-font libraries. */
    $businessSvgIcon = static function ($title, $section = '') {
        $titleContext = strtolower(trim((string) $title));
        $sectionContext = strtolower(trim((string) $section));
        $context = trim($titleContext . ' ' . $sectionContext);

        // Prefer the metric's own label first; a broad section name must not
        // turn every card in a section into the same generic icon.
        if (preg_match('/licen[cs]e|document|compliance|verification|identity/', $titleContext)) return 'id-card';
        if (preg_match('/maintenance|repair|service due|workshop/', $titleContext)) return 'wrench';
        if (preg_match('/attendance|late|punch|schedule|today/', $titleContext)) return 'clock';
        if (preg_match('/client.*driver|driver.*management|customers.*drivers|staff.*driver/', $titleContext)) return 'users';
        if (preg_match('/driver/', $titleContext)) return 'driver';
        if (preg_match('/client|customer|user|workforce|staff/', $titleContext)) return 'users';
        if (preg_match('/vehicle|fleet|truck/', $titleContext)) return 'truck';
        if (preg_match('/travel request|request pipeline|requests?/', $titleContext)) return 'inbox';
        if (preg_match('/duty|assignment|trip|route/', $titleContext)) return 'duty';
        if (preg_match('/work sheet|worksheet|duty report|work record/', $titleContext)) return 'clipboard';
        if (preg_match('/allowance|reimburse/', $titleContext)) return 'coins';
        if (preg_match('/expense|claim/', $titleContext)) return 'receipt';
        if (preg_match('/payroll|salary|payslip|payment/', $titleContext)) return 'wallet';
        if (preg_match('/price|pricing|rate|cost|amount|revenue/', $titleContext)) return 'rupee';
        if (preg_match('/category|categories|type|tag/', $titleContext)) return 'tags';
        if (preg_match('/audit|history|activity|system|health|access/', $titleContext)) return 'shield';
        if (preg_match('/image|media|optimisation|optimization/', $titleContext)) return 'image';
        if (preg_match('/trend|chart|performance|metric|report/', $titleContext)) return 'chart';

        if (preg_match('/licen[cs]e|document|compliance|verification|identity/', $context)) return 'id-card';
        if (preg_match('/maintenance|repair|service due|workshop/', $context)) return 'wrench';
        if (preg_match('/attendance|late|punch|schedule|today/', $context)) return 'clock';
        if (preg_match('/driver/', $context)) return 'driver';
        if (preg_match('/client|customer|user|workforce|staff/', $context)) return 'users';
        if (preg_match('/vehicle|fleet|truck/', $context)) return 'truck';
        if (preg_match('/travel request|request pipeline|requests?/', $context)) return 'inbox';
        if (preg_match('/duty|assignment|trip|route/', $context)) return 'duty';
        if (preg_match('/work sheet|worksheet|duty report|work record/', $context)) return 'clipboard';
        if (preg_match('/allowance|reimburse/', $context)) return 'coins';
        if (preg_match('/expense|claim/', $context)) return 'receipt';
        if (preg_match('/payroll|salary|payslip|payment/', $context)) return 'wallet';
        if (preg_match('/price|pricing|rate|cost|amount|revenue/', $context)) return 'rupee';
        if (preg_match('/category|categories|type|tag/', $context)) return 'tags';
        if (preg_match('/audit|history|activity|system|health|access/', $context)) return 'shield';
        if (preg_match('/image|media|optimisation|optimization/', $context)) return 'image';
        if (preg_match('/trend|chart|performance|metric|report/', $context)) return 'chart';
        if (preg_match('/approval|review|pending|open|inbox/', $context)) return 'inbox';

        return 'briefcase';
    };

    /** Standardise card data and keep all display formatting consistent. */
    $makeCard = static function (
        $section, $title, $key, $subtitle, $icon, $color, $status, $link, $routes = [], $format = 'number'
    ) use ($dashboardStats, $dashboardRoute, $businessSvgIcon) {
        $periodKey = $key . '_period';
        $usesReportingPeriod = array_key_exists($periodKey, $dashboardStats);
        $value = $usesReportingPeriod ? $dashboardStats[$periodKey] : ($dashboardStats[$key] ?? 0);
        if ($usesReportingPeriod) {
            $subtitle .= ' · Selected reporting period';
        }

        if ($format === 'currency') {
            $value = '₹ ' . number_format((float) $value, 2);
        } elseif (is_numeric($value)) {
            $value = number_format((float) $value, 0);
        }

        return [
            'section'  => $section,
            'title'    => $title,
            'value'    => $value,
            'subtitle' => $subtitle,
            'icon'     => $icon,
            'svg_icon' => $businessSvgIcon($title, $section),
            'color'    => $color,
            'status'   => $status,
            'link'     => $link,
            'url'      => $dashboardRoute(...$routes),
            'range_scoped' => $usesReportingPeriod,
        ];
    };

    $dashboardCards = [];

    if ($role === 'admin') {
        $dashboardCards = [
            $makeCard(
                'Client & Driver Management', 'Total Clients', 'total_clients',
                'All registered transport clients', 'dw dw-user-2', 'stat-blue',
                'Client master', 'View clients', ['client-management.index', 'clients.index']
            ),
            $makeCard(
                'Client & Driver Management', 'Active Clients', 'active_clients',
                'Clients currently enabled for operations', 'dw dw-user-2', 'stat-green',
                'Active records', 'View clients', ['client-management.index', 'clients.index']
            ),
            $makeCard(
                'Client & Driver Management', 'Inactive Clients', 'inactive_clients',
                'Client records needing review', 'dw dw-user-2', 'stat-orange',
                'Review queue', 'View clients', ['client-management.index', 'clients.index']
            ),
            $makeCard(
                'Client & Driver Management', 'Total Drivers', 'total_drivers',
                'Driver master records registered', 'dw dw-user-13', 'stat-purple',
                'Driver master', 'Manage driver records', ['driver-management.index', 'drivers.index']
            ),
            $makeCard(
                'Client & Driver Management', 'Active Drivers', 'active_drivers',
                'Drivers available for transport assignments', 'dw dw-user-13', 'stat-green',
                'Active workforce', 'Manage driver records', ['driver-management.index', 'drivers.index']
            ),
            $makeCard(
                'Client & Driver Management', 'Drivers on Leave', 'drivers_on_leave',
                'Leave status across driver profiles', 'dw dw-user-13', 'stat-orange',
                'Staff availability', 'Manage driver records', ['driver-management.index', 'drivers.index']
            ),
            $makeCard(
                'Client & Driver Management', 'Expired Licences', 'drivers_license_expired',
                'Driver licences marked expired', 'dw dw-file-1', 'stat-red',
                'Compliance alert', 'Review drivers', ['driver-management.index', 'drivers.index']
            ),
            $makeCard(
                'Client & Driver Management', 'Licences Expiring Soon', 'drivers_license_expiring_30_days',
                'Licences expiring within the next 30 days', 'dw dw-calendar', 'stat-orange',
                'Upcoming expiry', 'Review drivers', ['driver-management.index', 'drivers.index']
            ),
            $makeCard(
                'Client & Driver Management', 'Documents Pending', 'drivers_documents_pending',
                'Driver documents waiting for review', 'dw dw-file', 'stat-red',
                'Verification queue', 'Review drivers', ['driver-management.index', 'drivers.index']
            ),
            $makeCard(
                'Fleet & Vehicle Pricing', 'Total Vehicles', 'total_vehicles',
                'All fleet vehicles in the system', 'dw dw-car', 'stat-purple',
                'Fleet inventory', 'Manage fleet vehicles', ['vehicle-management.index', 'vehicles.index']
            ),
            $makeCard(
                'Fleet & Vehicle Pricing', 'Active Vehicles', 'active_vehicles',
                'Vehicles marked operational', 'dw dw-car', 'stat-green',
                'Fleet readiness', 'Manage fleet vehicles', ['vehicle-management.index', 'vehicles.index']
            ),
            $makeCard(
                'Fleet & Vehicle Pricing', 'Vehicles in Maintenance', 'maintenance_vehicles',
                'Vehicles requiring maintenance attention', 'dw dw-car', 'stat-orange',
                'Maintenance queue', 'Manage fleet vehicles', ['vehicle-management.index', 'vehicles.index']
            ),
            $makeCard(
                'Fleet & Vehicle Pricing', 'Vehicle Categories', 'vehicle_categories',
                'Active vehicle category records', 'dw dw-list', 'stat-cyan',
                'Fleet setup', 'View categories', ['vehicle-categories.index', 'vehicle-category.index']
            ),
            $makeCard(
                'Fleet & Vehicle Pricing', 'Active Vehicle Categories', 'active_vehicle_categories',
                'Categories available for assignment', 'dw dw-list', 'stat-green',
                'Configuration', 'View categories', ['vehicle-categories.index', 'vehicle-category.index']
            ),
            $makeCard(
                'Fleet & Vehicle Pricing', 'Vehicle Types', 'vehicle_types',
                'Vehicle type master records', 'dw dw-list', 'stat-indigo',
                'Fleet setup', 'View types', ['vehicle-types.index', 'vehicle-type.index']
            ),
            $makeCard(
                'Fleet & Vehicle Pricing', 'Active Vehicle Types', 'active_vehicle_types',
                'Types available for operations', 'dw dw-list', 'stat-green',
                'Configuration', 'View types', ['vehicle-types.index', 'vehicle-type.index']
            ),
            $makeCard(
                'Fleet & Vehicle Pricing', 'Vehicle Price Records', 'vehicle_prices',
                'Configured vehicle pricing records', 'dw dw-money', 'stat-blue',
                'Pricing master', 'View prices', ['vehicle-prices.index', 'vehicle-price.index']
            ),
            $makeCard(
                'Fleet & Vehicle Pricing', 'Current / Effective Prices', 'vehicle_prices_effective_on_or_before_today',
                'Pricing effective on or before today', 'dw dw-money-2', 'stat-teal',
                'Effective pricing', 'View prices', ['vehicle-prices.index', 'vehicle-price.index']
            ),
            $makeCard(
                'Fleet & Vehicle Pricing', 'Future Price Changes', 'future_vehicle_prices',
                'Future-dated vehicle price records', 'dw dw-calendar', 'stat-orange',
                'Upcoming changes', 'View prices', ['vehicle-prices.index', 'vehicle-price.index']
            ),
            $makeCard(
                'Travel Requests & Duty Management', 'Travel Requests', 'travel_requests',
                'All travel requests received', 'dw dw-file', 'stat-blue',
                'Request pipeline', 'View requests', ['travel-requests.index']
            ),
            $makeCard(
                'Travel Requests & Duty Management', 'Pending Requests', 'pending_travel_requests',
                'Travel requests awaiting decision', 'dw dw-file-1', 'stat-orange',
                'Pending action', 'Review requests', ['travel-requests.index']
            ),
            $makeCard(
                'Travel Requests & Duty Management', 'Approved Requests', 'approved_travel_requests',
                'Requests approved for fulfillment', 'dw dw-check', 'stat-green',
                'Approved', 'View requests', ['travel-requests.index']
            ),
            $makeCard(
                'Travel Requests & Duty Management', 'Assigned Requests', 'assigned_travel_requests',
                'Requests already assigned for execution', 'dw dw-briefcase', 'stat-indigo',
                'In execution', 'View requests', ['travel-requests.index']
            ),
            $makeCard(
                'Travel Requests & Duty Management', 'Duty Assignments', 'duty_assignments',
                'Total driver duty assignment records', 'dw dw-briefcase', 'stat-purple',
                'Duty management', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'Travel Requests & Duty Management', 'Pending Assignments', 'pending_duty_assignments',
                'Assignments awaiting dispatch or action', 'dw dw-file-1', 'stat-orange',
                'Needs attention', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'Travel Requests & Duty Management', 'Completed Duties', 'completed_duties',
                'Assignments marked completed', 'dw dw-check', 'stat-green',
                'Completed', 'Duty history', ['duty-assignments.index']
            ),
            $makeCard(
                'Travel Requests & Duty Management', 'Open Duty Slips', 'pending_duty_slips',
                'Duty slips waiting to be completed', 'dw dw-file-1', 'stat-red',
                'Open slips', 'View slips', ['duty-slips.index']
            ),
            $makeCard(
                'Travel Requests & Duty Management', 'Completed Duty Slips', 'completed_duty_slips',
                'Duty slips marked completed', 'dw dw-check', 'stat-teal',
                'Completed records', 'View slips', ['duty-slips.index']
            ),
            $makeCard(
                'Driver Attendance & Work Sheets', 'Working Sheets', 'working_sheets',
                'All operational driver work sheets', 'dw dw-file', 'stat-teal',
                'Daily operations', 'View sheets', ['working-sheets.index']
            ),
            $makeCard(
                'Driver Attendance & Work Sheets', 'Submitted Working Sheets', 'submitted_working_sheets',
                'Sheets submitted for review', 'dw dw-file-1', 'stat-orange',
                'Review queue', 'View sheets', ['working-sheets.index']
            ),
            $makeCard(
                'Driver Attendance & Work Sheets', 'Today\'s Working Sheets', 'today_working_sheets',
                'Driver work sheets recorded for today', 'dw dw-calendar', 'stat-cyan',
                'Daily activity', 'View sheets', ['working-sheets.index']
            ),
            $makeCard(
                'Driver Attendance & Work Sheets', 'Today\'s Attendance', 'attendance_today',
                'Attendance records recorded today', 'dw dw-calendar', 'stat-blue',
                'Attendance control', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'Driver Attendance & Work Sheets', 'Present / On Duty Today', 'present_today',
                'Drivers marked present or on duty today', 'dw dw-check', 'stat-green',
                'Workforce status', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'Driver Attendance & Work Sheets', 'Attendance Awaiting Review', 'pending_attendance_records',
                'Attendance records in pending status', 'dw dw-file-1', 'stat-orange',
                'Follow-up required', 'Review attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Allowance Types', 'allowances',
                'Configured allowance master records', 'dw dw-list', 'stat-blue',
                'Allowance master', 'View allowances', ['allowances.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Active Allowance Types', 'active_allowances',
                'Allowance types available for use', 'dw dw-check', 'stat-green',
                'Enabled', 'View allowances', ['allowances.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Pending Driver Allowance Claims', 'pending_driver_allowances',
                'Driver allowance claims awaiting review', 'dw dw-money', 'stat-orange',
                'Claim approval', 'Review claims', ['driver-allowances.index', 'driver-allowance.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Pending Allowance Amount', 'pending_driver_allowance_amount',
                'Total amount currently claimed and pending', 'dw dw-money-2', 'stat-red',
                'Financial review', 'Review claims', ['driver-allowances.index', 'driver-allowance.index'], 'currency'
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Expense Types', 'expense_types',
                'Configured expense category records', 'dw dw-list', 'stat-blue',
                'Expense master', 'View expenses', ['expenses.index', 'expense-types.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Active Expense Types', 'active_expense_types',
                'Expense types available for submission', 'dw dw-check', 'stat-green',
                'Enabled', 'View expenses', ['expenses.index', 'expense-types.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Pending Driver Expenses', 'pending_driver_expenses',
                'Driver expense claims awaiting review', 'dw dw-wallet', 'stat-orange',
                'Claim approval', 'Review expenses', ['driver-expenses.index', 'driver-expense.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Pending Expense Amount', 'pending_driver_expense_amount',
                'Total pending driver expense claim amount', 'dw dw-money-2', 'stat-red',
                'Financial review', 'Review expenses', ['driver-expenses.index', 'driver-expense.index'], 'currency'
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Payroll Batches Pending', 'pending_payroll',
                'Salary processing batches still in draft / pending state', 'dw dw-money', 'stat-orange',
                'Payroll queue', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Processed Payroll Batches', 'processed_payroll',
                'Salary processing batches marked processed', 'dw dw-money-2', 'stat-green',
                'Processed', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Approved Payroll', 'approved_payroll',
                'Payroll batches approved for payment', 'dw dw-check', 'stat-teal',
                'Approved', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Paid Payroll Batches', 'paid_payroll',
                'Payroll batches recorded as paid', 'dw dw-money-2', 'stat-green',
                'Paid', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Salary Slips', 'salary_slips',
                'Generated or issued salary slips', 'dw dw-file', 'stat-purple',
                'Payroll documents', 'View slips', ['salary-slips.index']
            ),
            $makeCard(
                'Expenses, Allowances & Payroll', 'Unpaid Salary Slips', 'unpaid_salary_slips',
                'Generated / issued slips with unpaid status', 'dw dw-file-1', 'stat-red',
                'Payment follow-up', 'View slips', ['salary-slips.index']
            ),
            $makeCard(
                'Compliance, Audit & System Health', 'Total Users', 'total_users',
                'Accounts across configured business roles', 'dw dw-user-2', 'stat-blue',
                'Access control', 'View users', ['user-management.index', 'users.index']
            ),
            $makeCard(
                'Compliance, Audit & System Health', 'Active Users', 'active_users',
                'User accounts currently active', 'dw dw-user-13', 'stat-green',
                'Active access', 'View users', ['user-management.index', 'users.index']
            ),
            $makeCard(
                'Compliance, Audit & System Health', 'Audit History Records', 'user_history_records',
                'Recorded user activity history entries', 'dw dw-list', 'stat-indigo',
                'Audit trail', 'View history', ['user-history.index', 'audit-logs.index']
            ),
            $makeCard(
                'Compliance, Audit & System Health', 'Recent Audit Activity', 'user_history_last_7_days',
                'User history entries from the last seven days', 'dw dw-calendar', 'stat-cyan',
                'Recent activity', 'View history', ['user-history.index', 'audit-logs.index']
            ),
            $makeCard(
                'Compliance, Audit & System Health', 'Image Metadata Records', 'image_metadata_records',
                'Stored image metadata records', 'dw dw-image', 'stat-purple',
                'Media inventory', 'View media', ['image-meta.index', 'image-metadata.index']
            ),
            $makeCard(
                'Compliance, Audit & System Health', 'Image Optimisation Logs', 'image_optimization_logs',
                'Image optimisation job log records', 'dw dw-list', 'stat-orange',
                'System logging', 'View logs', ['image-optimization-logs.index', 'optimization-logs.index']
            ),
            $makeCard(
                'Compliance, Audit & System Health', 'Recent Optimisation Logs', 'image_optimization_logs_last_7_days',
                'Image optimisation logs from the last seven days', 'dw dw-calendar', 'stat-cyan',
                'Recent system activity', 'View logs', ['image-optimization-logs.index', 'optimization-logs.index']
            ),
        ];
    } elseif ($role === 'operations') {
        $dashboardCards = [
            $makeCard(
                'Fleet Readiness & Availability', 'Active Clients', 'total_clients',
                'Active client records available for operations', 'dw dw-user-2', 'stat-blue',
                'Client availability', 'View clients', ['client-management.index', 'clients.index']
            ),
            $makeCard(
                'Fleet Readiness & Availability', 'Active Drivers', 'active_drivers',
                'Drivers available for transport assignments', 'dw dw-user-13', 'stat-green',
                'Workforce readiness', 'Manage driver records', ['driver-management.index', 'drivers.index']
            ),
            $makeCard(
                'Fleet Readiness & Availability', 'Drivers on Leave', 'drivers_on_leave',
                'Driver availability constraint', 'dw dw-user-13', 'stat-orange',
                'Availability', 'Manage driver records', ['driver-management.index', 'drivers.index']
            ),
            $makeCard(
                'Fleet Readiness & Availability', 'Licences Expiring Soon', 'drivers_license_expiring_30_days',
                'Licences expiring within 30 days', 'dw dw-file-1', 'stat-red',
                'Compliance follow-up', 'Review drivers', ['driver-management.index', 'drivers.index']
            ),
            $makeCard(
                'Fleet Readiness & Availability', 'Fleet Vehicles', 'total_vehicles',
                'Total registered fleet vehicles', 'dw dw-car', 'stat-purple',
                'Fleet inventory', 'Manage fleet vehicles', ['vehicle-management.index', 'vehicles.index']
            ),
            $makeCard(
                'Fleet Readiness & Availability', 'Available Vehicles', 'available_vehicles',
                'Vehicles marked active and ready by fleet status', 'dw dw-car', 'stat-green',
                'Availability', 'Manage fleet vehicles', ['vehicle-management.index', 'vehicles.index']
            ),
            $makeCard(
                'Fleet Readiness & Availability', 'Vehicles in Maintenance', 'maintenance_vehicles',
                'Vehicles currently in maintenance', 'dw dw-car', 'stat-orange',
                'Maintenance queue', 'Manage fleet vehicles', ['vehicle-management.index', 'vehicles.index']
            ),
            $makeCard(
                'Fleet Readiness & Availability', 'Vehicle Categories', 'vehicle_categories',
                'Active vehicle category records', 'dw dw-list', 'stat-cyan',
                'Fleet configuration', 'View categories', ['vehicle-categories.index', 'vehicle-category.index']
            ),
            $makeCard(
                'Fleet Readiness & Availability', 'Vehicle Types', 'vehicle_types',
                'Active vehicle type records', 'dw dw-list', 'stat-indigo',
                'Fleet configuration', 'View types', ['vehicle-types.index', 'vehicle-type.index']
            ),
            $makeCard(
                'Travel Request Management', 'Travel Requests', 'travel_requests',
                'All requests visible to operations', 'dw dw-file', 'stat-blue',
                'Request pipeline', 'View requests', ['travel-requests.index']
            ),
            $makeCard(
                'Travel Request Management', 'Pending Requests', 'pending_travel_requests',
                'Requests awaiting review / action', 'dw dw-file-1', 'stat-orange',
                'Needs attention', 'Review requests', ['travel-requests.index']
            ),
            $makeCard(
                'Travel Request Management', 'Approved Requests', 'approved_travel_requests',
                'Requests approved to proceed', 'dw dw-check', 'stat-green',
                'Approved', 'View requests', ['travel-requests.index']
            ),
            $makeCard(
                'Travel Request Management', 'Rejected Requests', 'rejected_travel_requests',
                'Requests marked rejected', 'dw dw-file-1', 'stat-red',
                'Rejected', 'Review requests', ['travel-requests.index']
            ),
            $makeCard(
                'Travel Request Management', 'Assigned Requests', 'assigned_travel_requests',
                'Requests already assigned for execution', 'dw dw-briefcase', 'stat-indigo',
                'Assigned', 'View requests', ['travel-requests.index']
            ),
            $makeCard(
                'Duty Assignment & Execution', 'Pending Duty Assignments', 'pending_duty_assignments',
                'Assignments waiting to be dispatched', 'dw dw-file-1', 'stat-orange',
                'Pending dispatch', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'Duty Assignment & Execution', 'Assigned Duties', 'assigned_duty_assignments',
                'Duties assigned to drivers', 'dw dw-briefcase', 'stat-blue',
                'Assigned', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'Duty Assignment & Execution', 'Accepted Duties', 'accepted_duty_assignments',
                'Duties accepted by assigned drivers', 'dw dw-check', 'stat-cyan',
                'Accepted', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'Duty Assignment & Execution', 'Started Duties', 'started_duty_assignments',
                'Duties already in progress', 'dw dw-time', 'stat-orange',
                'In progress', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'Duty Assignment & Execution', 'Completed Duties', 'completed_duties',
                'Duties marked completed', 'dw dw-check', 'stat-green',
                'Completed', 'Duty history', ['duty-assignments.index']
            ),
            $makeCard(
                'Duty Assignment & Execution', 'Today\'s Duty Assignments', 'today_duty_assignments',
                'Assignments created / recorded for today', 'dw dw-calendar', 'stat-cyan',
                'Daily dispatch', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'Duty Assignment & Execution', 'Open Duty Slips', 'open_duty_slips',
                'Duty slips that still need action', 'dw dw-file-1', 'stat-red',
                'Open slips', 'View slips', ['duty-slips.index']
            ),
            $makeCard(
                'Duty Assignment & Execution', 'Started Duty Slips', 'started_duty_slips',
                'Duty slips for journeys in progress', 'dw dw-time', 'stat-orange',
                'In progress', 'View slips', ['duty-slips.index']
            ),
            $makeCard(
                'Duty Assignment & Execution', 'Completed Duty Slips', 'completed_duty_slips',
                'Duty slips completed by drivers', 'dw dw-check', 'stat-green',
                'Completed', 'View slips', ['duty-slips.index']
            ),
            $makeCard(
                'Daily Duty Records & Claims', 'Working Sheets', 'working_sheets',
                'All driver work sheets on record', 'dw dw-file', 'stat-teal',
                'Operational records', 'View sheets', ['working-sheets.index']
            ),
            $makeCard(
                'Daily Duty Records & Claims', 'Submitted Working Sheets', 'submitted_working_sheets',
                'Sheets awaiting review', 'dw dw-file-1', 'stat-orange',
                'Review queue', 'View sheets', ['working-sheets.index']
            ),
            $makeCard(
                'Daily Duty Records & Claims', 'Approved Working Sheets', 'approved_working_sheets',
                'Sheets approved by the workflow', 'dw dw-check', 'stat-green',
                'Approved', 'View sheets', ['working-sheets.index']
            ),
            $makeCard(
                'Daily Duty Records & Claims', 'Today\'s Attendance', 'attendance_today',
                'Driver attendance records logged today', 'dw dw-calendar', 'stat-blue',
                'Attendance', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'Daily Duty Records & Claims', 'Present / On Duty', 'present_today',
                'Drivers present or on duty today', 'dw dw-check', 'stat-green',
                'Staffing status', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'Daily Duty Records & Claims', 'Absent Today', 'absent_today',
                'Drivers marked absent today', 'dw dw-user-13', 'stat-red',
                'Attendance alert', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'Daily Duty Records & Claims', 'Late Today', 'late_today',
                'Attendance entries marked late today', 'dw dw-time', 'stat-orange',
                'Attendance alert', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'Daily Duty Records & Claims', 'Pending Attendance', 'pending_attendance_records',
                'Attendance records requiring follow-up', 'dw dw-file-1', 'stat-orange',
                'Follow-up', 'Review attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'Daily Duty Records & Claims', 'Pending Driver Allowance Claims', 'pending_driver_allowances',
                'Count of allowance claims awaiting review', 'dw dw-money', 'stat-orange',
                'Claim review', 'Review claims', ['driver-allowances.index', 'driver-allowance.index']
            ),
            $makeCard(
                'Daily Duty Records & Claims', 'Pending Expense Reimbursements', 'pending_driver_expenses',
                'Count of expense claims awaiting review', 'dw dw-wallet', 'stat-red',
                'Claim review', 'Review expenses', ['driver-expenses.index', 'driver-expense.index']
            ),
        ];
    } elseif ($role === 'accountant') {
        $dashboardCards = [
            $makeCard(
                'Payroll Management', 'Payroll Pending', 'pending_payroll',
                'Salary processing batches awaiting processing', 'dw dw-money', 'stat-orange',
                'Pending batches', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index']
            ),
            $makeCard(
                'Payroll Management', 'Payroll Processed', 'processed_payroll',
                'Salary processing batches marked processed', 'dw dw-money-2', 'stat-green',
                'Processed', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index']
            ),
            $makeCard(
                'Payroll Management', 'Payroll Approved', 'approved_payroll',
                'Salary batches approved for payment', 'dw dw-check', 'stat-teal',
                'Approved', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index']
            ),
            $makeCard(
                'Payroll Management', 'Payroll Paid', 'paid_payroll',
                'Salary batches recorded as paid', 'dw dw-money-2', 'stat-green',
                'Paid', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index']
            ),
            $makeCard(
                'Payroll Management', 'Pending Payroll Value', 'pending_payroll_net_amount',
                'Net salary value in draft / pending batches', 'dw dw-money', 'stat-red',
                'Financial review', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index'], 'currency'
            ),
            $makeCard(
                'Payroll Management', 'Processed Payroll Value', 'processed_payroll_net_amount',
                'Net salary value processed', 'dw dw-money-2', 'stat-blue',
                'Processed value', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index'], 'currency'
            ),
            $makeCard(
                'Payroll Management', 'Approved Payroll Value', 'approved_payroll_net_amount',
                'Net salary value approved', 'dw dw-money-2', 'stat-cyan',
                'Approved value', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index'], 'currency'
            ),
            $makeCard(
                'Payroll Management', 'Paid Payroll Value', 'paid_payroll_net_amount',
                'Net salary value recorded as paid', 'dw dw-money-2', 'stat-green',
                'Paid value', 'View payroll', ['salary-processing.index', 'salary-processings.index', 'payroll.index'], 'currency'
            ),
            $makeCard(
                'Salary & Payslip Records', 'Salary Slips', 'salary_slips',
                'Generated or issued salary slips', 'dw dw-file', 'stat-purple',
                'Payroll documents', 'View slips', ['salary-slips.index']
            ),
            $makeCard(
                'Salary & Payslip Records', 'Unpaid Salary Slips', 'unpaid_salary_slips',
                'Salary slips requiring payment follow-up', 'dw dw-file-1', 'stat-red',
                'Payment follow-up', 'View slips', ['salary-slips.index']
            ),
            $makeCard(
                'Salary & Payslip Records', 'Partially Paid Slips', 'partially_paid_salary_slips',
                'Salary slips recorded with partial payment', 'dw dw-money', 'stat-orange',
                'Partial payments', 'View slips', ['salary-slips.index']
            ),
            $makeCard(
                'Salary & Payslip Records', 'Paid Salary Slips', 'paid_salary_slips',
                'Salary slips recorded as fully paid', 'dw dw-check', 'stat-green',
                'Paid', 'View slips', ['salary-slips.index']
            ),
            $makeCard(
                'Driver Allowance Claims', 'Allowance Types', 'allowances',
                'Configured allowance types', 'dw dw-list', 'stat-blue',
                'Allowance master', 'View allowances', ['allowances.index']
            ),
            $makeCard(
                'Driver Allowance Claims', 'Pending Driver Allowance Claims', 'pending_driver_allowances',
                'Claims awaiting accountant review', 'dw dw-money', 'stat-orange',
                'Pending review', 'Review claims', ['driver-allowances.index', 'driver-allowance.index']
            ),
            $makeCard(
                'Driver Allowance Claims', 'Approved Driver Allowance Claims', 'approved_driver_allowances',
                'Claims approved by workflow', 'dw dw-check', 'stat-green',
                'Approved', 'Review claims', ['driver-allowances.index', 'driver-allowance.index']
            ),
            $makeCard(
                'Driver Allowance Claims', 'Pending Allowance Value', 'pending_driver_allowance_amount',
                'Amount awaiting allowance approval', 'dw dw-money-2', 'stat-red',
                'Pending value', 'Review claims', ['driver-allowances.index', 'driver-allowance.index'], 'currency'
            ),
            $makeCard(
                'Expense Reimbursements', 'Active Expense Types', 'active_expense_types',
                'Expense types enabled for submission', 'dw dw-list', 'stat-blue',
                'Expense master', 'View expenses', ['expenses.index', 'expense-types.index']
            ),
            $makeCard(
                'Expense Reimbursements', 'Pending Expenses', 'pending_expenses',
                'Driver expense claims awaiting review', 'dw dw-wallet', 'stat-orange',
                'Pending review', 'Review expenses', ['driver-expenses.index', 'driver-expense.index']
            ),
            $makeCard(
                'Expense Reimbursements', 'Approved Expenses', 'approved_expenses',
                'Driver expense claims approved', 'dw dw-check', 'stat-green',
                'Approved', 'Review expenses', ['driver-expenses.index', 'driver-expense.index']
            ),
            $makeCard(
                'Expense Reimbursements', 'Pending Expense Value', 'pending_expense_amount',
                'Expense amount awaiting review', 'dw dw-money-2', 'stat-red',
                'Pending value', 'Review expenses', ['driver-expenses.index', 'driver-expense.index'], 'currency'
            ),
            $makeCard(
                'Payroll & Payment Readiness', 'Active Drivers for Payroll', 'active_drivers_for_payroll',
                'Active driver profiles available for payroll', 'dw dw-user-13', 'stat-blue',
                'Payroll preparation', 'Manage driver records', ['driver-management.index', 'drivers.index']
            ),
            $makeCard(
                'Payroll & Payment Readiness', 'Attendance Pending', 'attendance_pending',
                'Attendance records still pending', 'dw dw-calendar', 'stat-orange',
                'Payroll input', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'Payroll & Payment Readiness', 'Missing Punch Records', 'attendance_missing_punch',
                'Attendance entries with missing punch data', 'dw dw-time', 'stat-red',
                'Data correction', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'Payroll & Payment Readiness', 'Submitted Working Sheets', 'submitted_working_sheets',
                'Submitted sheets to review before payroll closure', 'dw dw-file', 'stat-orange',
                'Operational input', 'View sheets', ['working-sheets.index']
            ),
            $makeCard(
                'Payroll & Payment Readiness', 'Approved Working Sheets', 'approved_working_sheets',
                'Approved sheets available for payroll checking', 'dw dw-check', 'stat-green',
                'Approved input', 'View sheets', ['working-sheets.index']
            ),
        ];
    } elseif ($role === 'driver') {
        $dashboardCards = [
            $makeCard(
                'My Assigned Duties', 'Assigned Duties', 'assigned_duties',
                'Your assigned duty records', 'dw dw-briefcase', 'stat-blue',
                'Assigned', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'My Assigned Duties', 'Today\'s Duties', 'today_duties',
                'Your duties scheduled for today', 'dw dw-calendar', 'stat-orange',
                'Today', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'My Assigned Duties', 'Pending Duties', 'pending_duty_assignments',
                'Assignments awaiting your action', 'dw dw-file-1', 'stat-orange',
                'Needs action', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'My Assigned Duties', 'Accepted Duties', 'accepted_duty_assignments',
                'Assignments accepted by you', 'dw dw-check', 'stat-cyan',
                'Accepted', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'My Assigned Duties', 'Duties In Progress', 'started_duty_assignments',
                'Assignments currently in progress', 'dw dw-time', 'stat-orange',
                'In progress', 'View duties', ['duty-assignments.index']
            ),
            $makeCard(
                'My Assigned Duties', 'Open Duty Slips', 'open_duty_slips',
                'Duty slips still requiring completion', 'dw dw-file-1', 'stat-red',
                'Open slips', 'View slips', ['duty-slips.index']
            ),
            $makeCard(
                'My Assigned Duties', 'Completed Duties', 'completed_duties',
                'Your completed duty assignments', 'dw dw-check', 'stat-green',
                'Completed', 'Duty history', ['duty-assignments.index']
            ),
            $makeCard(
                'My Work Sheets & Duty Reports', 'Working Sheets', 'working_sheets',
                'Your driver work sheet records', 'dw dw-file', 'stat-teal',
                'Records', 'View sheets', ['working-sheets.index']
            ),
            $makeCard(
                'My Work Sheets & Duty Reports', 'Draft Sheets', 'draft_working_sheets',
                'Sheets not yet submitted', 'dw dw-file-1', 'stat-orange',
                'Draft', 'View sheets', ['working-sheets.index']
            ),
            $makeCard(
                'My Work Sheets & Duty Reports', 'Submitted Sheets', 'submitted_working_sheets',
                'Sheets submitted for approval', 'dw dw-check', 'stat-blue',
                'Submitted', 'View sheets', ['working-sheets.index']
            ),
            $makeCard(
                'My Work Sheets & Duty Reports', 'Approved Sheets', 'approved_working_sheets',
                'Sheets approved by the workflow', 'dw dw-check', 'stat-green',
                'Approved', 'View sheets', ['working-sheets.index']
            ),
            $makeCard(
                'My Driver Attendance', 'Attendance Today', 'attendance_today',
                'Your attendance record for today', 'dw dw-calendar', 'stat-blue',
                'Today', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'My Driver Attendance', 'Present / On Duty Today', 'present_today',
                'Today’s present or on-duty state', 'dw dw-check', 'stat-green',
                'Attendance', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'My Driver Attendance', 'Present Days This Month', 'present_days_this_month',
                'Present / on-duty days this month', 'dw dw-calendar', 'stat-cyan',
                'Monthly summary', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'My Driver Attendance', 'Late Days This Month', 'late_days_this_month',
                'Attendance entries marked late this month', 'dw dw-time', 'stat-orange',
                'Monthly summary', 'View attendance', ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']
            ),
            $makeCard(
                'My Expense Claims', 'Pending Allowances', 'pending_allowances',
                'Your allowance claims awaiting review', 'dw dw-money', 'stat-orange',
                'Pending', 'View claims', ['driver-allowances.index', 'driver-allowance.index']
            ),
            $makeCard(
                'My Expense Claims', 'Approved Allowances', 'approved_allowances',
                'Your allowance claims approved', 'dw dw-check', 'stat-green',
                'Approved', 'View claims', ['driver-allowances.index', 'driver-allowance.index']
            ),
            $makeCard(
                'My Expense Claims', 'Pending Allowance Value', 'pending_allowance_amount',
                'Amount currently pending for your allowances', 'dw dw-money-2', 'stat-red',
                'Pending value', 'View claims', ['driver-allowances.index', 'driver-allowance.index'], 'currency'
            ),
            $makeCard(
                'My Expense Claims', 'Pending Expenses', 'pending_expenses',
                'Your expense claims awaiting review', 'dw dw-wallet', 'stat-orange',
                'Pending', 'View expenses', ['driver-expenses.index', 'driver-expense.index']
            ),
            $makeCard(
                'My Expense Claims', 'Approved Expenses', 'approved_expenses',
                'Your expense claims approved', 'dw dw-check', 'stat-green',
                'Approved', 'View expenses', ['driver-expenses.index', 'driver-expense.index']
            ),
            $makeCard(
                'My Expense Claims', 'Pending Expense Value', 'pending_expense_amount',
                'Amount currently pending for your expense claims', 'dw dw-money-2', 'stat-red',
                'Pending value', 'View expenses', ['driver-expenses.index', 'driver-expense.index'], 'currency'
            ),
            $makeCard(
                'My Salary & Payslips', 'Current Salary', 'current_salary',
                'Latest salary figure available to your account', 'dw dw-money', 'stat-cyan',
                'Salary summary', 'View salary', ['salary-slips.index'], 'currency'
            ),
            $makeCard(
                'My Salary & Payslips', 'Pending Salary', 'pending_salary',
                'Salary value currently pending in your records', 'dw dw-money-2', 'stat-red',
                'Pending payment', 'View salary', ['salary-slips.index'], 'currency'
            ),
            $makeCard(
                'My Salary & Payslips', 'Salary Processing Records', 'salary_processing_records',
                'Salary processing records linked to your driver profile', 'dw dw-file', 'stat-purple',
                'Payroll records', 'View salary', ['salary-processing.index', 'salary-processings.index', 'payroll.index']
            ),
            $makeCard(
                'My Salary & Payslips', 'Unpaid Salary Slips', 'unpaid_salary_slips',
                'Salary slips marked unpaid', 'dw dw-file-1', 'stat-orange',
                'Payment status', 'View slips', ['salary-slips.index']
            ),
            $makeCard(
                'My Salary & Payslips', 'Paid Salary Slips', 'paid_salary_slips',
                'Salary slips marked paid', 'dw dw-check', 'stat-green',
                'Paid', 'View slips', ['salary-slips.index']
            ),
        ];
    }

    $dashboardCardCount = count($dashboardCards);
    $dashboardSectionCounts = [];
    $dashboardLinkedCardCount = 0;

    foreach ($dashboardCards as $dashboardCard) {
        $sectionName = $dashboardCard['section'] ?? 'Overview';
        $dashboardSectionCounts[$sectionName] = ($dashboardSectionCounts[$sectionName] ?? 0) + 1;

        if (!empty($dashboardCard['url']) && $dashboardCard['url'] !== '#') {
            $dashboardLinkedCardCount++;
        }
    }

    // Group the prepared cards for workflow-first navigation in the UI.
    $dashboardSections = collect($dashboardCards)->groupBy(function ($dashboardCard) {
        return $dashboardCard['section'] ?? 'Overview';
    });

    $dashboardRange = $dashboardStats['dashboard_range'] ?? [];
    $selectedDashboardPeriod = $dashboardRange['period'] ?? request()->query('dashboard_period', '30d');
    $selectedDashboardStart = $dashboardRange['start'] ?? request()->query('dashboard_start', now()->subDays(29)->toDateString());
    $selectedDashboardEnd = $dashboardRange['end'] ?? request()->query('dashboard_end', today()->toDateString());
    $todayOperations = collect($dashboardStats['today_operations'] ?? []);

    /*
    | Recent activity is read from the existing UserHistory model.
    | This keeps this Blade view self-contained without adding a route,
    | controller, or separate location-capture partial.
    */
    $activityStartDate = $dashboardRange['start'] ?? now()->subDays(29)->toDateString();
    $activityEndDate = $dashboardRange['end'] ?? today()->toDateString();
    $activitySearch = trim((string) request()->query('activity_q', ''));
    $activityDateFromInput = request()->query('activity_date_from');
    $activityDateToInput = request()->query('activity_date_to');
    $activityLocationFilter = (string) request()->query('activity_location', 'all');
    if (!in_array($activityLocationFilter, ['all', 'precise', 'approximate', 'not_shared'], true)) {
        $activityLocationFilter = 'all';
    }
    $normaliseActivityDate = static function ($value): ?string {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }
        [$year, $month, $day] = array_map('intval', explode('-', $value));
        return checkdate($month, $day, $year) ? $value : null;
    };
    $activityDateFrom = $normaliseActivityDate($activityDateFromInput);
    $activityDateTo = $normaliseActivityDate($activityDateToInput);
    $recentActivity = collect();

    if ($user && in_array($role, ['admin', 'driver'], true)) {
        $recentHistoryQuery = \App\Models\UserHistory::query()
            ->with('user:id,name,email')
            ->whereDate('activity_time', '>=', $activityStartDate)
            ->whereDate('activity_time', '<=', $activityEndDate);

        if ($activitySearch !== '') {
            $searchTerm = '%' . addcslashes($activitySearch, '%_\\') . '%';
            $recentHistoryQuery->where(function ($query) use ($searchTerm) {
                $query->where('activity', 'like', $searchTerm)
                    ->orWhere('ip_address', 'like', $searchTerm)
                    ->orWhere('device', 'like', $searchTerm)
                    ->orWhere('city', 'like', $searchTerm)
                    ->orWhere('country', 'like', $searchTerm)
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', $searchTerm)
                            ->orWhere('email', 'like', $searchTerm);
                    });
            });
        }

        if ($activityDateFrom) {
            $recentHistoryQuery->whereDate('activity_time', '>=', $activityDateFrom);
        }
        if ($activityDateTo) {
            $recentHistoryQuery->whereDate('activity_time', '<=', $activityDateTo);
        }

        if ($activityLocationFilter === 'precise') {
            $recentHistoryQuery->whereNotNull('latitude')->whereNotNull('longitude');
        } elseif ($activityLocationFilter === 'approximate') {
            $recentHistoryQuery
                ->where(function ($query) {
                    $query->whereNull('latitude')->orWhereNull('longitude');
                })
                ->where(function ($query) {
                    $query->where(function ($locationQuery) {
                        $locationQuery->whereNotNull('city')->where('city', '!=', '');
                    })->orWhere(function ($locationQuery) {
                        $locationQuery->whereNotNull('country')->where('country', '!=', '');
                    });
                });
        } elseif ($activityLocationFilter === 'not_shared') {
            $recentHistoryQuery
                ->whereNull('latitude')
                ->whereNull('longitude')
                ->where(function ($query) {
                    $query->whereNull('city')->orWhere('city', '');
                })
                ->where(function ($query) {
                    $query->whereNull('country')->orWhere('country', '');
                });
        }

        // Drivers can only see records belonging to their own account.
        if ($role === 'driver') {
            $recentHistoryQuery->where('user_id', $user->id);
        }

        $recentActivity = $recentHistoryQuery
            ->orderByDesc('activity_time')
            ->limit(100)
            ->get()
            ->map(static function (\App\Models\UserHistory $history) {
                return [
                    'user_id' => $history->user_id,
                    'actor' => $history->user?->name ?? ('User #' . $history->user_id),
                    'event_type' => $history->activity ?? 'activity',
                    'activity' => $history->activity ?? 'Activity recorded',
                    'time' => $history->activity_time?->format('d M Y, h:i A') ?? 'Time not recorded',
                    'device' => $history->device,
                    'ip_address' => $history->ip_address,
                    'city' => $history->city,
                    'country' => $history->country,
                    'latitude' => $history->latitude,
                    'longitude' => $history->longitude,
                    'location_accuracy' => $history->location_accuracy,
                    'location_captured_at' => $history->location_captured_at?->format('d M Y, h:i A'),
                ];
            });
    }

    $userDirectory = $dashboardStats['user_directory'] ?? null;
    $trendDaily = collect(data_get($dashboardStats, 'trend_series.daily', []));
    $trendPayroll = collect(data_get($dashboardStats, 'trend_series.payroll', []));
    $trendFleet = collect(data_get($dashboardStats, 'trend_series.fleet', []));
    $maxTravelTrend = max(1, (int) $trendDaily->max('travel_requests'));
    $maxCompletedTrend = max(1, (int) $trendDaily->max('completed_duties'));
    $maxPayrollTrend = max(1, (float) $trendPayroll->max('net_amount'));
    $maxFleetTrend = max(1, (int) $trendFleet->max('value'));

    // Action-center items are based only on keys supplied by DashboardService.
    // A missing metric is omitted rather than shown as an invented zero.
    $prioritySpecsByRole = [
        'admin' => [
            ['key' => 'pending_travel_requests', 'title' => 'Travel requests awaiting review', 'routes' => ['travel-requests.index'], 'icon' => 'fa-inbox', 'kind' => 'warning'],
            ['key' => 'unassigned_travel_requests', 'title' => 'Travel requests without a duty assignment', 'routes' => ['travel-requests.index'], 'icon' => 'fa-chain-broken', 'kind' => 'warning'],
            ['key' => 'pending_duty_assignments', 'title' => 'Duty assignments awaiting action', 'routes' => ['duty-assignments.index'], 'icon' => 'fa-calendar-check-o', 'kind' => 'warning'],
            ['key' => 'overdue_duty_slips', 'title' => 'Overdue duty slips', 'routes' => ['duty-slips.index'], 'icon' => 'fa-clock-o', 'kind' => 'danger'],
            ['key' => 'maintenance_vehicles', 'title' => 'Vehicles in maintenance', 'routes' => ['vehicle-management.index', 'vehicles.index'], 'icon' => 'fa-wrench', 'kind' => 'warning'],
            ['key' => 'drivers_license_expired', 'title' => 'Expired driver licences', 'routes' => ['driver-management.index', 'drivers.index'], 'icon' => 'fa-id-card-o', 'kind' => 'danger'],
            ['key' => 'drivers_documents_pending', 'title' => 'Driver documents pending review', 'routes' => ['driver-management.index', 'drivers.index'], 'icon' => 'fa-file-text-o', 'kind' => 'warning'],
            ['key' => 'pending_driver_expenses', 'title' => 'Expense claims awaiting review', 'routes' => ['driver-expenses.index', 'driver-expense.index'], 'icon' => 'fa-credit-card', 'kind' => 'warning'],
            ['key' => 'pending_driver_allowances', 'title' => 'Allowance claims awaiting review', 'routes' => ['driver-allowances.index', 'driver-allowance.index'], 'icon' => 'fa-money', 'kind' => 'warning'],
            ['key' => 'pending_payroll', 'title' => 'Payroll batches awaiting processing', 'routes' => ['salary-processing.index', 'salary-processings.index', 'payroll.index'], 'icon' => 'fa-calculator', 'kind' => 'warning'],
            ['key' => 'unpaid_salary_slips', 'title' => 'Unpaid salary slips', 'routes' => ['salary-slips.index'], 'icon' => 'fa-file-text-o', 'kind' => 'warning'],
        ],
        'operations' => [
            ['key' => 'pending_travel_requests', 'title' => 'Travel requests awaiting review', 'routes' => ['travel-requests.index'], 'icon' => 'fa-inbox', 'kind' => 'warning'],
            ['key' => 'unassigned_travel_requests', 'title' => 'Requests without a duty assignment', 'routes' => ['travel-requests.index'], 'icon' => 'fa-chain-broken', 'kind' => 'warning'],
            ['key' => 'pending_duty_assignments', 'title' => 'Duty assignments awaiting action', 'routes' => ['duty-assignments.index'], 'icon' => 'fa-calendar-check-o', 'kind' => 'warning'],
            ['key' => 'overdue_duty_slips', 'title' => 'Overdue duty slips', 'routes' => ['duty-slips.index'], 'icon' => 'fa-clock-o', 'kind' => 'danger'],
            ['key' => 'maintenance_vehicles', 'title' => 'Vehicles in maintenance', 'routes' => ['vehicle-management.index', 'vehicles.index'], 'icon' => 'fa-wrench', 'kind' => 'warning'],
            ['key' => 'late_today', 'title' => 'Late attendance entries today', 'routes' => ['driver-attendance.index', 'driver-attendances.index', 'attendance.index'], 'icon' => 'fa-clock-o', 'kind' => 'warning'],
            ['key' => 'missing_punch_today', 'title' => 'Missing attendance punches today', 'routes' => ['driver-attendance.index', 'driver-attendances.index', 'attendance.index'], 'icon' => 'fa-exclamation-circle', 'kind' => 'danger'],
            ['key' => 'pending_driver_expenses', 'title' => 'Expense claims awaiting review', 'routes' => ['driver-expenses.index', 'driver-expense.index'], 'icon' => 'fa-credit-card', 'kind' => 'warning'],
            ['key' => 'pending_driver_allowances', 'title' => 'Allowance claims awaiting review', 'routes' => ['driver-allowances.index', 'driver-allowance.index'], 'icon' => 'fa-money', 'kind' => 'warning'],
            ['key' => 'submitted_working_sheets', 'title' => 'Driver work sheets awaiting review', 'routes' => ['working-sheets.index'], 'icon' => 'fa-file-text-o', 'kind' => 'warning'],
        ],
        'accountant' => [
            ['key' => 'pending_payroll', 'title' => 'Payroll batches awaiting processing', 'routes' => ['salary-processing.index', 'salary-processings.index', 'payroll.index'], 'icon' => 'fa-calculator', 'kind' => 'warning'],
            ['key' => 'pending_expenses', 'title' => 'Expense claims awaiting approval', 'routes' => ['driver-expenses.index', 'driver-expense.index'], 'icon' => 'fa-credit-card', 'kind' => 'warning'],
            ['key' => 'pending_driver_allowances', 'title' => 'Allowance claims awaiting approval', 'routes' => ['driver-allowances.index', 'driver-allowance.index'], 'icon' => 'fa-money', 'kind' => 'warning'],
            ['key' => 'unpaid_salary_slips', 'title' => 'Unpaid salary slips', 'routes' => ['salary-slips.index'], 'icon' => 'fa-file-text-o', 'kind' => 'warning'],
            ['key' => 'attendance_pending', 'title' => 'Attendance records awaiting review', 'routes' => ['driver-attendance.index', 'driver-attendances.index', 'attendance.index'], 'icon' => 'fa-calendar', 'kind' => 'warning'],
            ['key' => 'attendance_missing_punch', 'title' => 'Attendance records with missing punches', 'routes' => ['driver-attendance.index', 'driver-attendances.index', 'attendance.index'], 'icon' => 'fa-exclamation-circle', 'kind' => 'danger'],
            ['key' => 'submitted_working_sheets', 'title' => 'Driver work sheets awaiting payroll review', 'routes' => ['working-sheets.index'], 'icon' => 'fa-file-text-o', 'kind' => 'warning'],
        ],
        'driver' => [
            ['key' => 'pending_duty_assignments', 'title' => 'Duties awaiting your response', 'routes' => ['duty-assignments.index'], 'icon' => 'fa-calendar-check-o', 'kind' => 'warning'],
            ['key' => 'overdue_duty_slips', 'title' => 'Overdue duty slips', 'routes' => ['duty-slips.index'], 'icon' => 'fa-clock-o', 'kind' => 'danger'],
            ['key' => 'open_duty_slips', 'title' => 'Duty slips still to complete', 'routes' => ['duty-slips.index'], 'icon' => 'fa-file-text-o', 'kind' => 'warning'],
            ['key' => 'draft_working_sheets', 'title' => 'Driver work sheets still in draft', 'routes' => ['working-sheets.index'], 'icon' => 'fa-pencil-square-o', 'kind' => 'warning'],
            ['key' => 'rejected_working_sheets', 'title' => 'Driver work sheets returned for correction', 'routes' => ['working-sheets.index'], 'icon' => 'fa-undo', 'kind' => 'danger'],
            ['key' => 'missing_punch_records', 'title' => 'Attendance records with missing punches', 'routes' => ['driver-attendance.index', 'driver-attendances.index', 'attendance.index'], 'icon' => 'fa-exclamation-circle', 'kind' => 'danger'],
            ['key' => 'pending_allowances', 'title' => 'Allowance claims awaiting review', 'routes' => ['driver-allowances.index', 'driver-allowance.index'], 'icon' => 'fa-money', 'kind' => 'warning'],
            ['key' => 'pending_expenses', 'title' => 'Expense claims awaiting review', 'routes' => ['driver-expenses.index', 'driver-expense.index'], 'icon' => 'fa-credit-card', 'kind' => 'warning'],
        ],
    ];

    $dashboardPriorityItems = [];
    foreach (($prioritySpecsByRole[$role] ?? []) as $prioritySpec) {
        if (!array_key_exists($prioritySpec['key'], $dashboardStats)) {
            continue;
        }
        $priorityValue = $dashboardStats[$prioritySpec['key']];
        if (!is_numeric($priorityValue) || (float) $priorityValue <= 0) {
            continue;
        }
        $prioritySpec['value'] = $priorityValue;
        $prioritySpec['url'] = $dashboardRoute(...$prioritySpec['routes']);
        $dashboardPriorityItems[] = $prioritySpec;
    }

    // Quick actions are role-scoped and only rendered when an appropriate route exists.
    $quickActionSpecsByRole = [
        'admin' => [
            ['label' => 'Add client', 'description' => 'Create a client record', 'icon' => 'fa-user-plus', 'routes' => ['client-management.create', 'clients.create']],
            ['label' => 'Add driver profile', 'description' => 'Add a driver profile', 'icon' => 'fa-id-card-o', 'routes' => ['driver-management.create', 'drivers.create']],
            ['label' => 'Add vehicle', 'description' => 'Register a fleet vehicle', 'icon' => 'fa-truck', 'routes' => ['vehicle-management.create', 'vehicles.create']],
            ['label' => 'Assign driver & vehicle', 'description' => 'Create a duty assignment', 'icon' => 'fa-calendar-plus-o', 'routes' => ['duty-assignments.create']],
            ['label' => 'Record attendance', 'description' => 'Open attendance workflow', 'icon' => 'fa-calendar-check-o', 'routes' => ['driver-attendance.create', 'driver-attendances.create', 'attendance.create']],
            ['label' => 'Review expenses', 'description' => 'Review driver expense claims', 'icon' => 'fa-credit-card', 'routes' => ['driver-expenses.index', 'driver-expense.index']],
            ['label' => 'Process payroll', 'description' => 'Open salary processing', 'icon' => 'fa-calculator', 'routes' => ['salary-processing.index', 'salary-processings.index', 'payroll.index']],
        ],
        'operations' => [
            ['label' => 'New travel request', 'description' => 'Open the request workflow', 'icon' => 'fa-plus-circle', 'routes' => ['travel-requests.create']],
            ['label' => 'Assign driver & vehicle', 'description' => 'Allocate a driver and vehicle', 'icon' => 'fa-calendar-plus-o', 'routes' => ['duty-assignments.create']],
            ['label' => 'Record attendance', 'description' => 'Open attendance workflow', 'icon' => 'fa-calendar-check-o', 'routes' => ['driver-attendance.create', 'driver-attendances.create', 'attendance.create']],
            ['label' => 'Review driver work sheets', 'description' => 'Check submitted sheets', 'icon' => 'fa-file-text-o', 'routes' => ['working-sheets.index']],
            ['label' => 'Manage fleet vehicles', 'description' => 'Check vehicle readiness', 'icon' => 'fa-truck', 'routes' => ['vehicle-management.index', 'vehicles.index']],
        ],
        'accountant' => [
            ['label' => 'Process payroll', 'description' => 'Review salary batches', 'icon' => 'fa-calculator', 'routes' => ['salary-processing.index', 'salary-processings.index', 'payroll.index']],
            ['label' => 'Review expenses', 'description' => 'Review expense claims', 'icon' => 'fa-credit-card', 'routes' => ['driver-expenses.index', 'driver-expense.index']],
            ['label' => 'Review allowances', 'description' => 'Review driver allowance claims', 'icon' => 'fa-money', 'routes' => ['driver-allowances.index', 'driver-allowance.index']],
            ['label' => 'View salary slips', 'description' => 'Review payment statuses', 'icon' => 'fa-file-text-o', 'routes' => ['salary-slips.index']],
            ['label' => 'Review driver work sheets', 'description' => 'Check payroll inputs', 'icon' => 'fa-list-alt', 'routes' => ['working-sheets.index']],
        ],
        'driver' => [
            ['label' => 'My duties', 'description' => 'Open assigned duties', 'icon' => 'fa-calendar-check-o', 'routes' => ['duty-assignments.index']],
            ['label' => 'Duty slips', 'description' => 'Complete duty records', 'icon' => 'fa-file-text-o', 'routes' => ['duty-slips.index']],
            ['label' => 'Driver work sheets', 'description' => 'Submit work details', 'icon' => 'fa-list-alt', 'routes' => ['working-sheets.index']],
            ['label' => 'My attendance', 'description' => 'Review attendance records', 'icon' => 'fa-calendar', 'routes' => ['driver-attendance.index', 'driver-attendances.index', 'attendance.index']],
            ['label' => 'My salary', 'description' => 'View salary records', 'icon' => 'fa-money', 'routes' => ['salary-slips.index']],
        ],
    ];
    $dashboardQuickActions = [];
    foreach (($quickActionSpecsByRole[$role] ?? []) as $quickActionSpec) {
        $quickActionUrl = $dashboardRoute(...$quickActionSpec['routes']);
        if ($quickActionUrl === '#') {
            continue;
        }
        $quickActionSpec['url'] = $quickActionUrl;
        $dashboardQuickActions[] = $quickActionSpec;
    }
@endphp


{{-- =========================================================
    CLIENT-FRIENDLY DASHBOARD HEADER
========================================================= --}}

<section class="dashboard-welcome-panel dashboard-header" aria-labelledby="dashboard-page-title">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <div class="dashboard-welcome-main">
            <div class="dashboard-eyebrow">
                <i class="fa fa-line-chart" aria-hidden="true"></i>
                Operations at a glance
            </div>
            <h2 id="dashboard-page-title">Transport Business Operations Dashboard</h2>
            <p>
                Welcome back, <strong>{{ $user?->name ?? 'User' }}</strong>.
                Here is your role-based business overview.
            </p>
            <div class="dashboard-page-meta">
                <span><i class="fa fa-calendar-o" aria-hidden="true"></i>{{ now()->format('D, d M Y') }}</span>
                <span class="dashboard-meta-separator" aria-hidden="true">•</span>
                <span><i class="fa fa-th-large" aria-hidden="true"></i>{{ number_format($dashboardCardCount) }} available metrics</span>
            </div>
        </div>

        <div class="dashboard-welcome-side">
            <div class="dashboard-role-badge" aria-label="Current role: {{ ucfirst($role ?: 'user') }}">
                <span class="role-dot" aria-hidden="true"></span>
                {{ ucfirst(str_replace('_', ' ', $role ?: 'User')) }} account
            </div>
        </div>
    </div>
</section>

{{-- =========================================================
    DASHBOARD AT-A-GLANCE SUMMARY
========================================================= --}}

<div class="dashboard-summary-strip" aria-label="Dashboard summary">
    <div class="dashboard-summary-item">
        <div class="dashboard-summary-icon" aria-hidden="true"><svg class="dashboard-business-icon" viewBox="0 0 24 24" focusable="false"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V4M4 20h17"/><rect x="7" y="12" width="3" height="5"/><rect x="13" y="8" width="3" height="9"/><rect x="19" y="5" width="2" height="12"/></g></svg></div>
        <div class="dashboard-summary-copy">
            <span class="dashboard-summary-value">{{ number_format($dashboardCardCount) }}</span>
            <span class="dashboard-summary-label">Available business metrics</span>
        </div>
    </div>
    <div class="dashboard-summary-item">
        <div class="dashboard-summary-icon" aria-hidden="true"><svg class="dashboard-business-icon" viewBox="0 0 24 24" focusable="false"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="8" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/><rect x="13" y="13" width="8" height="8" rx="1.5"/></g></svg></div>
        <div class="dashboard-summary-copy">
            <span class="dashboard-summary-value">{{ number_format($dashboardSections->count()) }}</span>
            <span class="dashboard-summary-label">Workflow sections</span>
        </div>
    </div>
    <div class="dashboard-summary-item">
        <div class="dashboard-summary-icon" aria-hidden="true"><svg class="dashboard-business-icon" viewBox="0 0 24 24" focusable="false"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.1 0l2-2a5 5 0 0 0-7.1-7.1l-1.1 1.1"/><path d="M14 11a5 5 0 0 0-7.1 0l-2 2A5 5 0 0 0 12 20l1.1-1.1"/></g></svg></div>
        <div class="dashboard-summary-copy">
            <span class="dashboard-summary-value">{{ number_format($dashboardLinkedCardCount) }}</span>
            <span class="dashboard-summary-label">Metrics with a module link</span>
        </div>
    </div>
</div>

{{-- =========================================================
    SEARCH, FILTERS AND RESULT COUNT
========================================================= --}}

<section class="dashboard-controls" aria-label="Find transport business metrics">
    <div class="dashboard-controls-top">
        <div>
            <h3 class="dashboard-controls-title">Business metric directory</h3>
            <p class="dashboard-controls-hint">All role-relevant business cards are shown above the operational panels. Search by module, status, or description.</p>
        </div>
        <div class="dashboard-search-wrap">
            <i class="fa fa-search" aria-hidden="true"></i>
            <label class="dashboard-sr-only" for="dashboard-metric-search">Search dashboard metrics</label>
            <input
                id="dashboard-metric-search"
                class="dashboard-search-input"
                type="search"
                placeholder="Try “pending payroll”, “drivers”, “vehicles”…"
                autocomplete="off"
                aria-controls="dashboard-sections-list"
            >
            <button type="button" class="dashboard-search-clear" id="dashboard-search-clear" aria-label="Clear search">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <div class="dashboard-filter-row" role="group" aria-label="Filter dashboard metrics">
        <span class="dashboard-filter-label">Show:</span>
        <button type="button" class="dashboard-filter-button is-active" data-dashboard-filter="all" aria-pressed="true">
            <i class="fa fa-th-large" aria-hidden="true"></i> All metrics
        </button>
        <button type="button" class="dashboard-filter-button" data-dashboard-filter="attention" aria-pressed="false">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Needs attention
        </button>
        <button type="button" class="dashboard-filter-button" data-dashboard-filter="linked" aria-pressed="false">
            <i class="fa fa-link" aria-hidden="true"></i> Quick links
        </button>
        <span class="dashboard-results-count" id="dashboard-results-count" role="status" aria-live="polite">
            Showing <strong>{{ number_format($dashboardCardCount) }}</strong> of <strong>{{ number_format($dashboardCardCount) }}</strong> metrics
        </span>
    </div>
</section>

{{-- =========================================================
    BUSINESS METRIC SECTIONS (VISIBLE BY DEFAULT)
========================================================= --}}

<div id="dashboard-sections-list" class="dashboard-sections-list">
    @forelse($dashboardSections as $sectionName => $sectionCards)
        <section class="dashboard-section" data-dashboard-section>
            <div class="dashboard-section-summary">
                <div class="dashboard-section-heading">
                    @php $sectionSvgIcon = $businessSvgIcon($sectionName, $sectionName); @endphp
                    <span class="dashboard-section-marker" aria-hidden="true">
                        <svg class="dashboard-business-icon" viewBox="0 0 24 24" focusable="false">
                            <g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                @if($sectionSvgIcon === 'truck')
                                    <path d="M3 6h11v11H3zM14 10h4l3 3v4h-7z"/><circle cx="7.5" cy="18.5" r="1.8"/><circle cx="17.5" cy="18.5" r="1.8"/>
                                @elseif($sectionSvgIcon === 'driver' || $sectionSvgIcon === 'users')
                                    <circle cx="12" cy="7" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>
                                @elseif($sectionSvgIcon === 'wallet' || $sectionSvgIcon === 'coins' || $sectionSvgIcon === 'receipt')
                                    <rect x="3" y="5" width="18" height="15" rx="2"/><path d="M3 8h18M15 13h6v4h-6a2 2 0 0 1 0-4z"/>
                                @elseif($sectionSvgIcon === 'clock')
                                    <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>
                                @elseif($sectionSvgIcon === 'rupee')
                                    <path d="M5 4h14M5 8h14M7 4c7 0 7 8 0 8H5l10 8"/>
                                @elseif($sectionSvgIcon === 'shield' || $sectionSvgIcon === 'id-card')
                                    <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/><path d="m9 12 2 2 4-4"/>
                                @elseif($sectionSvgIcon === 'duty' || $sectionSvgIcon === 'inbox')
                                    <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 12h5l2 3h4l2-3h5"/>
                                @else
                                    <rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V2h6v2M8 10h8M8 14h8"/>
                                @endif
                            </g>
                        </svg>
                    </span>
                    <div class="dashboard-section-heading-copy">
                        <h3>{{ $sectionName }}</h3>
                        <p>Explore the key metrics and actions for this workflow</p>
                    </div>
                </div>
                <span class="dashboard-section-trailing">
                    <span class="dashboard-section-count" data-section-visible-count>{{ number_format(count($sectionCards)) }}</span>
                </span>
            </div>

            <div class="dashboard-section-content">
                <div class="row dashboard-section-grid">
                    @foreach($sectionCards as $card)
                        @php
                            $cardSearchText = strtolower(trim(
                                ($card['title'] ?? '') . ' ' .
                                ($card['subtitle'] ?? '') . ' ' .
                                ($card['status'] ?? '') . ' ' .
                                ($card['section'] ?? '') . ' ' .
                                ($card['link'] ?? '')
                            ));
                            $cardAttentionText = strtolower(
                                ($card['title'] ?? '') . ' ' .
                                ($card['subtitle'] ?? '') . ' ' .
                                ($card['status'] ?? '')
                            );
                            $isAttentionCard = (bool) preg_match(
                                '/pending|expired|expiring|maintenance|unpaid|open|overdue|inactive|late|awaiting|failed|missing|rejected|draft|due|unapproved|review|not processed|not completed|on leave/i',
                                $cardAttentionText
                            );
                            $hasCardLink = !empty($card['url']) && $card['url'] !== '#';
                        @endphp

                        <div
                            class="col-xl-3 col-lg-4 col-md-6 mb-20 dashboard-stat-card"
                            data-dashboard-card
                            data-search="{{ $cardSearchText }}"
                            data-attention="{{ $isAttentionCard ? 'true' : 'false' }}"
                            data-linked="{{ $hasCardLink ? 'true' : 'false' }}"
                            style="--card-index:{{ $loop->index % 18 }}"
                        >
                            <article class="premium-stat-card {{ $card['color'] }}">
                                <div class="premium-stat-body">
                                    <div class="premium-stat-top">
                                        <div class="premium-stat-info">
                                            <div class="premium-stat-label" title="{{ $card['title'] }} — {{ $card['subtitle'] }}" tabindex="0" aria-label="{{ $card['title'] }}. {{ $card['subtitle'] }}">{{ $card['title'] }} <i class="fa fa-info-circle" aria-hidden="true" style="font-size:10px;color:#cbd5e1;margin-left:3px"></i></div>
                                            <div class="premium-stat-value">{{ $card['value'] }}</div>
                                            <div class="premium-stat-subtitle">{{ $card['subtitle'] }}</div>
                                        </div>
                                        @php $cardSvgIcon = $card['svg_icon'] ?? 'briefcase'; @endphp
                                        <div class="premium-stat-icon" aria-hidden="true">
                                            <svg class="dashboard-business-icon" viewBox="0 0 24 24" role="img" aria-label="{{ $cardSvgIcon }} icon" focusable="false">
                                                <g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    @if($cardSvgIcon === 'users')
                                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                                                    @elseif($cardSvgIcon === 'driver')
                                                        <circle cx="12" cy="7" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/><circle cx="12" cy="16" r="2.5"/><path d="M9.5 16H7m10 0h-2.5M12 18.5V21"/>
                                                    @elseif($cardSvgIcon === 'truck')
                                                        <path d="M3 6h11v11H3zM14 10h4l3 3v4h-7z"/><circle cx="7.5" cy="18.5" r="1.8"/><circle cx="17.5" cy="18.5" r="1.8"/>
                                                    @elseif($cardSvgIcon === 'wrench')
                                                        <path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4l-3.1 3.1-3.8-3.8z"/>
                                                    @elseif($cardSvgIcon === 'clock')
                                                        <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>
                                                    @elseif($cardSvgIcon === 'duty')
                                                        <rect x="4" y="5" width="16" height="16" rx="2"/><path d="M8 3v4m8-4v4M4 10h16M8 14l2.5 2.5L16 12"/>
                                                    @elseif($cardSvgIcon === 'clipboard')
                                                        <rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V2h6v2M8 10h8M8 14h8M8 18h5"/>
                                                    @elseif($cardSvgIcon === 'receipt')
                                                        <path d="M5 3h14v18l-3-2-4 2-4-2-3 2zM8 8h8M8 12h8M8 16h4"/>
                                                    @elseif($cardSvgIcon === 'coins')
                                                        <ellipse cx="9" cy="6" rx="6" ry="3"/><path d="M3 6v5c0 1.7 2.7 3 6 3 1.2 0 2.3-.2 3.2-.5M3 11v5c0 1.7 2.7 3 6 3 1.3 0 2.5-.2 3.5-.6"/><ellipse cx="16" cy="15" rx="5" ry="2.5"/><path d="M11 15v4c0 1.4 2.2 2.5 5 2.5s5-1.1 5-2.5v-4"/>
                                                    @elseif($cardSvgIcon === 'wallet')
                                                        <rect x="3" y="5" width="18" height="15" rx="2"/><path d="M3 8h18M15 13h6v4h-6a2 2 0 0 1 0-4z"/><circle cx="16" cy="15" r=".5" fill="currentColor"/>
                                                    @elseif($cardSvgIcon === 'rupee')
                                                        <path d="M5 4h14M5 8h14M7 4c7 0 7 8 0 8H5l10 8"/>
                                                    @elseif($cardSvgIcon === 'id-card')
                                                        <rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8" cy="11" r="2"/><path d="M5.5 16a3 3 0 0 1 5 0M13 10h5M13 14h5"/>
                                                    @elseif($cardSvgIcon === 'file-check')
                                                        <path d="M6 3h8l4 4v7M14 3v5h5M6 3v18h7M9 13h2M9 17h2M14 18l2 2 5-5"/>
                                                    @elseif($cardSvgIcon === 'tags')
                                                        <path d="M3 12V4h8l10 10-7 7z"/><circle cx="7.5" cy="8" r="1"/>
                                                    @elseif($cardSvgIcon === 'shield')
                                                        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/><path d="m9 12 2 2 4-4"/>
                                                    @elseif($cardSvgIcon === 'image')
                                                        <rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m21 15-5-5L5 20"/>
                                                    @elseif($cardSvgIcon === 'chart')
                                                        <path d="M4 20V4M4 20h17"/><rect x="7" y="12" width="3" height="5" rx=".5"/><rect x="13" y="8" width="3" height="9" rx=".5"/><rect x="19" y="5" width="2" height="12" rx=".5"/>
                                                    @else
                                                        <rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V4h8v3M3 12h18M10 12v2h4v-2"/>
                                                    @endif
                                                </g>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="premium-stat-footer">
                                        <div class="premium-stat-status">
                                            <span class="status-dot" aria-hidden="true"></span>
                                            {{ $card['status'] }}
                                        </div>

                                        @if($hasCardLink)
                                            <a href="{{ $card['url'] }}" class="premium-stat-link">
                                                {{ $card['link'] }}
                                                <i class="fa fa-angle-right ml-1" aria-hidden="true"></i>
                                            </a>
                                        @else
                                            <span class="dashboard-no-route" title="No matching named route is registered for this module">
                                                {{ $card['link'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
                <div class="dashboard-section-empty" data-section-empty hidden>
                    <i class="fa fa-search" aria-hidden="true"></i>
                    No metrics in this section match the current search or filter.
                </div>
            </div>
        </section>
    @empty
        <div class="alert alert-warning" role="status">
            <h5 class="mb-5">Dashboard access is not configured</h5>
            <p class="mb-0">
                This account's role ({{ $role ?: 'not assigned' }}) does not have a dashboard card configuration.
                Contact the administrator to confirm the user role and DashboardService mapping.
            </p>
        </div>
    @endforelse
</div>

<div class="dashboard-no-results" id="dashboard-no-results" hidden role="status" aria-live="polite">
    <div class="dashboard-no-results-icon" aria-hidden="true"><i class="fa fa-search"></i></div>
    <h3>No matching metrics found</h3>
    <p>Try a different keyword or switch back to all metrics to see the full dashboard.</p>
    <button type="button" class="dashboard-reset-button" id="dashboard-reset-filters">
        <i class="fa fa-refresh" aria-hidden="true"></i> Reset search and filters
    </button>
</div>



{{-- =========================================================
    GRAPHICAL METRIC DISTRIBUTION
    This visualises the dashboard's configured metrics by workflow,
    not business performance or financial outcomes.
========================================================= --}}
@php
    $dashboardSectionColors = ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#f43f5e', '#06b6d4', '#64748b', '#ec4899'];
    $dashboardSectionMetricTotal = max(1, (int) $dashboardSections->sum(function ($items) { return count($items); }));
    $dashboardDonutOffset = 0.0;
    $dashboardDonutCircumference = 251.2;
    $dashboardMaxSectionCount = max(1, (int) $dashboardSections->max(function ($items) { return count($items); }));
@endphp
<section class="dashboard-visual-overview" aria-label="Graphical dashboard overview">
    <div class="dashboard-visual-card">
        <h3 class="dashboard-visual-heading">Metric distribution</h3>
        <p class="dashboard-visual-subtitle">How the available dashboard metrics are grouped by workflow.</p>
        @if($dashboardCardCount > 0)
            <div class="dashboard-donut-layout">
                <svg class="dashboard-donut" viewBox="0 0 120 120" role="img" aria-label="Donut chart showing {{ $dashboardCardCount }} configured metrics across {{ $dashboardSections->count() }} workflows">
                    <circle class="dashboard-donut-track" cx="60" cy="60" r="40"></circle>
                    @foreach($dashboardSections as $sectionName => $sectionCards)
                        @php
                            $segmentValue = count($sectionCards) / $dashboardSectionMetricTotal * $dashboardDonutCircumference;
                            $segmentColor = $dashboardSectionColors[$loop->index % count($dashboardSectionColors)];
                            $segmentOffset = $dashboardDonutOffset;
                            $dashboardDonutOffset += $segmentValue;
                        @endphp
                        <circle class="dashboard-donut-segment" cx="60" cy="60" r="40" stroke="{{ $segmentColor }}" stroke-dasharray="{{ number_format($segmentValue, 3, '.', '') }} {{ number_format($dashboardDonutCircumference - $segmentValue, 3, '.', '') }}" stroke-dashoffset="{{ number_format(-$segmentOffset, 3, '.', '') }}" style="animation-delay:{{ $loop->index * 90 }}ms"></circle>
                    @endforeach
                    <text class="dashboard-donut-center" x="60" y="58">{{ number_format($dashboardCardCount) }}</text>
                    <text class="dashboard-donut-caption" x="60" y="70">METRICS</text>
                </svg>
                <div class="dashboard-donut-legend">
                    @foreach($dashboardSections as $sectionName => $sectionCards)
                        <div class="dashboard-donut-legend-row">
                            <span class="dashboard-donut-swatch" style="background:{{ $dashboardSectionColors[$loop->index % count($dashboardSectionColors)] }}"></span>
                            <span>{{ $sectionName }}</span>
                            <strong>{{ count($sectionCards) }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="dashboard-empty-state"><i class="fa fa-pie-chart" aria-hidden="true"></i><strong>No metrics configured</strong><span>Metrics will appear here when they are available for your role.</span></div>
        @endif
    </div>
    <div class="dashboard-visual-card">
        <h3 class="dashboard-visual-heading">Workflow coverage</h3>
        <p class="dashboard-visual-subtitle">Choose a workflow bar to jump to its visible metric cards.</p>
        <div class="dashboard-section-bars">
            @foreach($dashboardSections as $sectionName => $sectionCards)
                @php $sectionPercent = round(count($sectionCards) / $dashboardMaxSectionCount * 100); @endphp
                <button type="button" class="dashboard-section-bar-row" data-jump-section="{{ \Illuminate\Support\Str::slug($sectionName) }}" style="border:0;background:transparent;text-align:left;width:100%">
                    <span class="dashboard-section-bar-head"><strong>{{ $sectionName }}</strong><span>{{ count($sectionCards) }} metrics · {{ round(count($sectionCards) / $dashboardSectionMetricTotal * 100) }}%</span></span>
                    <span class="dashboard-section-bar-track" aria-hidden="true"><span class="dashboard-section-bar-fill" style="--bar-width:{{ $sectionPercent }}%;--bar-delay:{{ $loop->index * 75 }}ms;display:block"></span></span>
                </button>
            @endforeach
        </div>
    </div>
</section>




{{-- =========================================================
    PRIORITY ACTION CENTER + ROLE-SCOPED QUICK ACTIONS
========================================================= --}}
<div class="dashboard-widget-grid">
    <section class="dashboard-widget-panel" aria-labelledby="dashboard-priority-title">
        <div class="dashboard-widget-header">
            <div class="dashboard-widget-heading">
                <span class="dashboard-widget-heading-icon is-warning" aria-hidden="true"><i class="fa fa-bell-o"></i></span>
                <div>
                    <h3 class="dashboard-widget-title" id="dashboard-priority-title">Needs attention</h3>
                    <p class="dashboard-widget-description">Open items that may need your next action.</p>
                </div>
            </div>
            <span class="dashboard-widget-total">{{ number_format(count($dashboardPriorityItems)) }} items</span>
        </div>
        <div class="dashboard-widget-body">
            @if(count($dashboardPriorityItems))
                <div class="dashboard-priority-list">
                    @foreach(array_slice($dashboardPriorityItems, 0, 8) as $item)
                        @php
                            $priorityLabel = is_numeric($item['value']) ? number_format((float) $item['value'], 0) : (string) $item['value'];
                            $priorityClass = ($item['kind'] ?? 'warning') === 'danger' ? 'is-danger' : '';
                        @endphp
                        @if(!empty($item['url']) && $item['url'] !== '#')
                            <a class="dashboard-priority-item dashboard-priority-link {{ $priorityClass }}" href="{{ $item['url'] }}">
                        @else
                            <div class="dashboard-priority-item {{ $priorityClass }}">
                        @endif
                                @php $prioritySvgIcon = $businessSvgIcon($item['title'] ?? '', 'priority'); @endphp
                                <span class="dashboard-priority-icon" aria-hidden="true">
                                    <svg class="dashboard-business-icon" viewBox="0 0 24 24" focusable="false"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        @if($prioritySvgIcon === 'truck')<path d="M3 6h11v11H3zM14 10h4l3 3v4h-7z"/><circle cx="7.5" cy="18.5" r="1.8"/><circle cx="17.5" cy="18.5" r="1.8"/>
                                        @elseif($prioritySvgIcon === 'clock')<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>
                                        @elseif($prioritySvgIcon === 'receipt')<path d="M5 3h14v18l-3-2-4 2-4-2-3 2zM8 8h8M8 12h8"/>
                                        @elseif($prioritySvgIcon === 'wallet')<rect x="3" y="5" width="18" height="15" rx="2"/><path d="M3 8h18M15 13h6v4h-6a2 2 0 0 1 0-4z"/>
                                        @elseif($prioritySvgIcon === 'duty' || $prioritySvgIcon === 'inbox')<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 12h5l2 3h4l2-3h5"/>
                                        @elseif($prioritySvgIcon === 'driver' || $prioritySvgIcon === 'users')<circle cx="12" cy="7" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>
                                        @elseif($prioritySvgIcon === 'coins')<ellipse cx="12" cy="6" rx="8" ry="3"/><path d="M4 6v5c0 4 16 4 16 0V6M4 11v5c0 4 16 4 16 0v-5"/>
                                        @else<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V2h6v2M8 10h8M8 14h8"/>
                                        @endif
                                    </g></svg>
                                </span>
                                <span class="dashboard-priority-copy">
                                    <strong>{{ $item['title'] }}</strong>
                                    <span>{{ ($item['kind'] ?? '') === 'danger' ? 'High priority' : 'Review when possible' }}</span>
                                </span>
                                <span class="dashboard-priority-count">{{ $priorityLabel }}</span>
                                @if(!empty($item['url']) && $item['url'] !== '#')
                                    <i class="fa fa-angle-right dashboard-priority-arrow" aria-hidden="true"></i>
                                @endif
                        @if(!empty($item['url']) && $item['url'] !== '#')
                            </a>
                        @else
                            </div>
                        @endif
                    @endforeach
                </div>
                @if(count($dashboardPriorityItems) > 8)
                    <p class="dashboard-data-note"><i class="fa fa-info-circle"></i><span>{{ count($dashboardPriorityItems) - 8 }} additional attention items are available in the metric sections below.</span></p>
                @endif
            @else
                <div class="dashboard-empty-state">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    <strong>You're all caught up</strong>
                    <span>No non-zero priority items were returned for this role. The current live queues are clear based on the available DashboardService metrics.</span>
                </div>
            @endif
        </div>
    </section>

    <section class="dashboard-widget-panel" aria-labelledby="dashboard-quick-title">
        <div class="dashboard-widget-header">
            <div class="dashboard-widget-heading">
                <span class="dashboard-widget-heading-icon" aria-hidden="true"><i class="fa fa-bolt"></i></span>
                <div>
                    <h3 class="dashboard-widget-title" id="dashboard-quick-title">Quick actions</h3>
                    <p class="dashboard-widget-description">Jump directly to the workflows relevant to your role.</p>
                </div>
            </div>
        </div>
        <div class="dashboard-widget-body">
            @if(count($dashboardQuickActions))
                <div class="dashboard-quick-grid">
                    @foreach($dashboardQuickActions as $action)
                        <a class="dashboard-quick-action" href="{{ $action['url'] }}">
                            @php $quickSvgIcon = $businessSvgIcon($action['label'] ?? '', $action['description'] ?? ''); @endphp
                            <span class="dashboard-quick-icon" aria-hidden="true">
                                <svg class="dashboard-business-icon" viewBox="0 0 24 24" focusable="false"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    @if($quickSvgIcon === 'truck')<path d="M3 6h11v11H3zM14 10h4l3 3v4h-7z"/><circle cx="7.5" cy="18.5" r="1.8"/><circle cx="17.5" cy="18.5" r="1.8"/>
                                    @elseif($quickSvgIcon === 'driver' || $quickSvgIcon === 'users')<circle cx="12" cy="7" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>
                                    @elseif($quickSvgIcon === 'wallet' || $quickSvgIcon === 'coins')<rect x="3" y="5" width="18" height="15" rx="2"/><path d="M3 8h18M15 13h6v4h-6a2 2 0 0 1 0-4z"/>
                                    @elseif($quickSvgIcon === 'clock' || $quickSvgIcon === 'duty')<rect x="4" y="5" width="16" height="16" rx="2"/><path d="M8 3v4m8-4v4M4 10h16M8 14l2.5 2.5L16 12"/>
                                    @elseif($quickSvgIcon === 'receipt')<path d="M5 3h14v18l-3-2-4 2-4-2-3 2zM8 8h8M8 12h8"/>
                                    @else<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V2h6v2M8 10h8M8 14h8"/>
                                    @endif
                                </g></svg>
                            </span>
                            <span class="dashboard-quick-copy"><strong>{{ $action['label'] }}</strong><span>{{ $action['description'] }}</span></span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="dashboard-empty-state">
                    <i class="fa fa-link" aria-hidden="true"></i>
                    <strong>No quick links configured</strong>
                    <span>No matching named routes were found for the current role. The actions will appear automatically when the relevant routes are registered.</span>
                </div>
            @endif
        </div>
    </section>
</div>

{{-- =========================================================
    TODAY'S OPERATIONS BOARD
========================================================= --}}
@if(in_array($role, ['admin', 'operations', 'driver'], true))
    <section class="dashboard-widget-panel dashboard-widget-panel-full" aria-labelledby="dashboard-today-title">
        <div class="dashboard-widget-header">
            <div class="dashboard-widget-heading">
                <span class="dashboard-widget-heading-icon is-purple" aria-hidden="true"><i class="fa fa-calendar-check-o"></i></span>
                <div>
                    <h3 class="dashboard-widget-title" id="dashboard-today-title">Today's operations</h3>
                    <p class="dashboard-widget-description">Duty slips scheduled for {{ now()->format('l, d M Y') }} with the latest stored status.</p>
                </div>
            </div>
            <div class="dashboard-widget-action-row">
                @php $todayDutyUrl = $dashboardRoute('duty-slips.index', 'duty-assignments.index'); @endphp
                @if($todayDutyUrl !== '#')
                    <a class="dashboard-small-button" href="{{ $todayDutyUrl }}">Open duty board <i class="fa fa-angle-right ml-1" aria-hidden="true"></i></a>
                @endif
            </div>
        </div>
        <div class="dashboard-widget-body">
            @if($todayOperations->isNotEmpty())
                <div class="dashboard-duty-table-wrap">
                    <table class="dashboard-duty-table">
                        <thead><tr><th>Time / Duty</th><th>Driver</th><th>Vehicle</th><th>Route</th><th>Status</th></tr></thead>
                        <tbody>
                        @foreach($todayOperations as $duty)
                            @php
                                $dutyStatus = strtolower((string) ($duty['status'] ?? 'pending'));
                                $dutyStatusClass = in_array($dutyStatus, ['completed'], true) ? 'is-complete' : (in_array($dutyStatus, ['cancelled', 'rejected'], true) ? 'is-danger' : (in_array($dutyStatus, ['started', 'accepted', 'assigned'], true) ? 'is-running' : ''));
                            @endphp
                            <tr>
                                <td><span class="dashboard-duty-code">{{ $duty['time'] ?? 'Time not set' }}</span><span class="dashboard-duty-secondary">{{ $duty['slip_no'] ?? 'Duty slip' }}</span></td>
                                <td>{{ $duty['driver'] ?? 'Driver not assigned' }}</td>
                                <td>{{ $duty['vehicle'] ?? 'Vehicle not allocated' }}</td>
                                <td>{{ $duty['route'] ?? 'Route not provided' }}</td>
                                <td><span class="dashboard-status-pill {{ $dutyStatusClass }}"><i class="fa fa-circle" style="font-size:6px" aria-hidden="true"></i>{{ str_replace('_', ' ', $dutyStatus) }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if($todayOperations->count() >= 12)
                    <p class="dashboard-data-note"><i class="fa fa-info-circle"></i><span>Showing the first 12 scheduled duty slips. Open the duty board for the complete list.</span></p>
                @endif
            @else
                <div class="dashboard-empty-state">
                    <i class="fa fa-calendar-o" aria-hidden="true"></i>
                    <strong>No duty slips scheduled for today</strong>
                    <span>No duty slips for today's date were returned for your role. Check the future schedule or create/assign a duty when a valid travel request is available.</span>
                    @if($todayDutyUrl !== '#') <a href="{{ $todayDutyUrl }}">View duty schedule</a> @endif
                </div>
            @endif
        </div>
    </section>
@endif

{{-- =========================================================
    PERFORMANCE TRENDS + REPORTING RANGE
========================================================= --}}
<section class="dashboard-widget-panel dashboard-widget-panel-full" aria-labelledby="dashboard-trends-title">
    <div class="dashboard-widget-header">
        <div class="dashboard-widget-heading">
            <span class="dashboard-widget-heading-icon" aria-hidden="true"><i class="fa fa-line-chart"></i></span>
            <div>
                <h3 class="dashboard-widget-title" id="dashboard-trends-title">Performance insights</h3>
                <p class="dashboard-widget-description">Real records for {{ $dashboardRange['label'] ?? 'the selected period' }}. Inventory and current approval queues remain live snapshots.</p>
            </div>
        </div>
        <div class="dashboard-widget-action-row">
            <button type="button" class="dashboard-small-button" id="dashboard-export-csv"><i class="fa fa-download mr-1" aria-hidden="true"></i>Export CSV</button>
            <button type="button" class="dashboard-small-button" id="dashboard-print-pdf"><i class="fa fa-file-pdf-o mr-1" aria-hidden="true"></i>Print / Save PDF</button>
        </div>
    </div>
    <div class="dashboard-widget-body">
        <div class="dashboard-widget-toolbar">
            <form method="GET" action="{{ url()->current() }}" class="dashboard-date-filter" id="dashboard-date-filter-form">
                <label class="dashboard-date-field">Reporting period
                    <select name="dashboard_period" id="dashboard-period-select">
                        <option value="7d" {{ ($selectedDashboardPeriod === '7d') ? 'selected' : '' }}>Last 7 days</option>
                        <option value="30d" {{ ($selectedDashboardPeriod === '30d') ? 'selected' : '' }}>Last 30 days</option>
                        <option value="custom" {{ ($selectedDashboardPeriod === 'custom') ? 'selected' : '' }}>Custom range</option>
                    </select>
                </label>
                <label class="dashboard-date-field">Start date
                    <input type="date" name="dashboard_start" id="dashboard-start-date" value="{{ $selectedDashboardStart }}" max="{{ today()->toDateString() }}" @disabled($selectedDashboardPeriod !== 'custom')>
                </label>
                <label class="dashboard-date-field">End date
                    <input type="date" name="dashboard_end" id="dashboard-end-date" value="{{ $selectedDashboardEnd }}" max="{{ today()->toDateString() }}" @disabled($selectedDashboardPeriod !== 'custom')>
                </label>
                <button type="submit" class="dashboard-small-button is-primary">Apply range</button>
            </form>
            <span class="dashboard-widget-description">Last updated: {{ !empty($dashboardRange['last_updated']) ? \Illuminate\Support\Carbon::parse($dashboardRange['last_updated'])->format('d M Y, h:i A') : now()->format('d M Y, h:i A') }}</span>
        </div>

        <div class="dashboard-chart-grid {{ $role === 'accountant' ? 'dashboard-chart-grid-single' : '' }}" style="margin-top:18px">
            @if(in_array($role, ['admin', 'operations', 'driver'], true))
            <div class="dashboard-chart-card">
                <h4 class="dashboard-chart-title">{{ $role === 'driver' ? 'Your daily duty completion' : 'Daily requests and completed duties' }}</h4>
                <p class="dashboard-chart-subtitle">{{ $role === 'driver' ? 'Only this driver account’s assignments are included.' : 'Requests use creation date. Completed duties use the record’s last-updated date as the completion proxy.' }}</p>
                <div class="dashboard-chart-legend">
                    @if($role !== 'driver') <span><i class="dashboard-legend-dot dashboard-legend-blue"></i>Travel requests</span> @endif
                    <span><i class="dashboard-legend-dot dashboard-legend-green"></i>{{ $role === 'driver' ? 'Your completed duties' : 'Completed duties' }}</span>
                </div>
                @if($trendDaily->isNotEmpty() && ($trendDaily->sum('travel_requests') + $trendDaily->sum('completed_duties')) > 0)
                    <div class="dashboard-chart-scroll">
                        <div class="dashboard-chart-bars" role="img" aria-label="{{ $role === 'driver' ? 'Daily completed duty counts for this driver' : 'Daily travel requests and completed duty counts for this reporting period' }}">
                            @foreach($trendDaily as $point)
                                @php
                                    $requestCount = (int) ($point['travel_requests'] ?? 0);
                                    $completedCount = (int) ($point['completed_duties'] ?? 0);
                                    $showDateLabel = $trendDaily->count() <= 10 || $loop->first || $loop->last || (($loop->iteration - 1) % 5 === 0);
                                @endphp
                                <div class="dashboard-chart-column" title="{{ $point['label'] ?? $point['date'] }}: @if($role !== 'driver'){{ $requestCount }} travel requests, @endif {{ $completedCount }} completed duties">
                                    <div class="dashboard-chart-bar-pair">
                                        @if($role !== 'driver')
                                            <span class="dashboard-chart-bar is-blue" style="height:{{ $requestCount > 0 ? max(5, round(($requestCount / $maxTravelTrend) * 100)) : 0 }}%"></span>
                                        @endif
                                        <span class="dashboard-chart-bar is-green" style="height:{{ $completedCount > 0 ? max(5, round(($completedCount / $maxCompletedTrend) * 100)) : 0 }}%"></span>
                                    </div>
                                    <span class="dashboard-chart-date">{{ $showDateLabel ? ($point['label'] ?? '') : '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="dashboard-empty-state"><i class="fa fa-bar-chart" aria-hidden="true"></i><strong>No activity in this period</strong><span>{{ $role === 'driver' ? 'No completed duties were recorded for your account in the selected date range.' : 'No travel requests or completed duties were recorded in the selected date range.' }}</span></div>
                @endif
            </div>
            @endif

            @if(in_array($role, ['admin', 'operations'], true))
                <div class="dashboard-chart-card">
                    <h4 class="dashboard-chart-title">Current fleet readiness</h4>
                    <p class="dashboard-chart-subtitle">Live inventory snapshot; historical status changes are not stored as a series in the supplied models.</p>
                    @if($trendFleet->isNotEmpty())
                        <div class="dashboard-fleet-list">
                            @foreach($trendFleet as $fleetItem)
                                @php
                                    $fleetValue = (int) ($fleetItem['value'] ?? 0);
                                    $fleetWidth = ((int) ($dashboardStats['total_vehicles'] ?? 0)) > 0 ? min(100, round(($fleetValue / max(1, (int) ($dashboardStats['total_vehicles'] ?? 0))) * 100)) : 0;
                                    $fleetClass = str_contains(strtolower($fleetItem['label'] ?? ''), 'maintenance') ? 'is-amber' : (str_contains(strtolower($fleetItem['label'] ?? ''), 'other') ? 'is-slate' : '');
                                @endphp
                                <div>
                                    <div class="dashboard-fleet-line"><span>{{ $fleetItem['label'] }}</span><strong>{{ number_format($fleetValue) }}</strong></div>
                                    <div class="dashboard-fleet-track"><div class="dashboard-fleet-fill {{ $fleetClass }}" style="width:{{ $fleetWidth }}%"></div></div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="dashboard-empty-state"><i class="fa fa-truck" aria-hidden="true"></i><strong>Fleet snapshot unavailable</strong><span>The service did not supply fleet metrics for this role.</span></div>
                    @endif
                </div>
            @endif

            @if(in_array($role, ['admin', 'accountant'], true))
                <div class="dashboard-chart-card">
                    <h4 class="dashboard-chart-title">Payroll processed by month</h4>
                    <p class="dashboard-chart-subtitle">Net salary for processed, approved and paid batches created in the selected reporting period.</p>
                    @if($trendPayroll->isNotEmpty() && $trendPayroll->sum('net_amount') > 0)
                        <div class="dashboard-bar-list">
                            @foreach($trendPayroll as $payrollPoint)
                                @php $payrollAmount = (float) ($payrollPoint['net_amount'] ?? 0); @endphp
                                <div class="dashboard-bar-row">
                                    <span class="dashboard-bar-label">{{ $payrollPoint['label'] }}</span>
                                    <div class="dashboard-bar-track"><div class="dashboard-bar-fill" style="width:{{ $payrollAmount > 0 ? min(100, round(($payrollAmount / $maxPayrollTrend) * 100)) : 0 }}%"></div></div>
                                    <span class="dashboard-bar-value">₹{{ number_format($payrollAmount, 0) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="dashboard-empty-state"><i class="fa fa-money" aria-hidden="true"></i><strong>No payroll activity in this period</strong><span>No processed, approved or paid salary batches were recorded in the selected date range.</span></div>
                    @endif
                </div>
            @endif
        </div>
        <div class="dashboard-data-note"><i class="fa fa-info-circle" aria-hidden="true"></i><span>The selected reporting period applies to date-based activity, duty, attendance, claims, payroll and audit metrics, as well as the charts and admin activity feed. Master inventory and current vehicle/driver readiness remain live snapshots; the Needs Attention panel also shows current queues so urgent work is never hidden by a date filter.</span></div>
    </div>
</section>

{{-- =========================================================
    MODEL-DRIVEN ACTIVITY REPORT AND ROLE-AWARE USER DIRECTORY
    Uses the existing UserHistory model and existing routes only.
========================================================= --}}
<?php if (in_array($role, ['admin', 'driver'], true)): ?>
    @php
        $activityResetQuery = request()->except([
            'activity_q', 'activity_date_from', 'activity_date_to', 'activity_location', 'page',
        ]);
    @endphp
    <section class="dashboard-widget-panel dashboard-widget-panel-full dashboard-audit-report-panel" aria-labelledby="dashboard-activity-title">
        <div class="dashboard-widget-header">
            <div class="dashboard-widget-heading">
                <span class="dashboard-widget-heading-icon is-success" aria-hidden="true"><i class="fa fa-history"></i></span>
                <div>
                    <h3 class="dashboard-widget-title" id="dashboard-activity-title">{{ $role === 'driver' ? 'My recent activity' : 'Latest audit events' }}</h3>
                    <p class="dashboard-widget-description">
                        {{ $role === 'driver' ? 'Only activity recorded against your own driver account is shown here.' : 'Recent UserHistory records in ' . ($dashboardRange['label'] ?? 'the selected reporting period') . '. Filter activity, dates and recorded location.' }}
                    </p>
                </div>
            </div>
            <span class="dashboard-widget-total">{{ number_format($recentActivity->count()) }} records</span>
        </div>

        <div class="dashboard-widget-body">
            <div class="dashboard-report-filter-card">
                <h4 class="dashboard-report-filter-heading">{{ $role === 'driver' ? 'My Activity Filters' : 'Audit Activity Filters' }}</h4>
                <form method="GET" action="{{ request()->url() }}" class="dashboard-report-filter" aria-label="Filter activity report">
                    <?php foreach (request()->query() as $queryKey => $queryValue): ?>
                        <?php if (!in_array($queryKey, ['activity_q', 'activity_date_from', 'activity_date_to', 'activity_location', 'page'], true) && is_scalar($queryValue)): ?>
                            <input type="hidden" name="{{ $queryKey }}" value="{{ $queryValue }}">
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <div class="dashboard-report-filter-field dashboard-report-search-field">
                        <label for="activity-q">Search</label>
                        <input id="activity-q" type="search" name="activity_q" value="{{ request('activity_q') }}" class="form-control" placeholder="User, activity, device, IP...">
                    </div>

                    <div class="dashboard-report-filter-field">
                        <label for="activity-location">Location</label>
                        <select id="activity-location" name="activity_location" class="form-control">
                            <option value="all" {{ ($activityLocationFilter === 'all') ? 'selected' : '' }}>All location records</option>
                            <option value="precise" {{ ($activityLocationFilter === 'precise') ? 'selected' : '' }}>Precise coordinates shared</option>
                            <option value="approximate" {{ ($activityLocationFilter === 'approximate') ? 'selected' : '' }}>Approximate location</option>
                            <option value="not_shared" {{ ($activityLocationFilter === 'not_shared') ? 'selected' : '' }}>Location not shared</option>
                        </select>
                    </div>

                    <div class="dashboard-report-filter-field">
                        <label for="activity-date-from">Date From</label>
                        <input id="activity-date-from" type="date" name="activity_date_from" value="{{ $activityDateFrom ?? '' }}" class="form-control">
                    </div>

                    <div class="dashboard-report-filter-field">
                        <label for="activity-date-to">Date To</label>
                        <input id="activity-date-to" type="date" name="activity_date_to" value="{{ $activityDateTo ?? '' }}" class="form-control">
                    </div>

                    <div class="dashboard-report-filter-actions">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
                        <a href="{{ request()->url() }}{{ count($activityResetQuery) ? '?' . http_build_query($activityResetQuery) : '' }}" class="btn btn-secondary"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                    </div>
                </form>
            </div>

            <div class="card-box mb-30 dashboard-report-table-card">
                <div class="pd-20">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                        <h4 class="dashboard-report-table-title">{{ $role === 'driver' ? 'My Activity Records' : 'All Audit Activity' }}</h4>
                        <span class="badge badge-primary">Total: {{ number_format($recentActivity->count()) }}</span>
                    </div>
                </div>

                <div class="pb-20">
                    <div class="table-responsive">
                        <table class="table hover nowrap p-3 dashboard-report-table" data-title="{{ $role === 'driver' ? 'My Activity Records' : 'Audit Activity' }}">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>User</th>
                                    <th>Activity</th>
                                    <th>Device</th>
                                    <th>IP Address</th>
                                    <th>Location</th>
                                    <th>Activity Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($recentActivity && $recentActivity->count()): ?>
                                <?php foreach ($recentActivity as $activityKey => $activity): ?>
                                    <tr>
                                        <td>{{ $activityKey + 1 }}</td>
                                        <td><strong class="text-dark">{{ $role === 'driver' ? 'You' : ($activity['actor'] ?? 'Unknown user') }}</strong></td>
                                        <td>
                                            <strong class="text-dark">{{ $activity['activity'] ?? 'Activity recorded' }}</strong>
                                            <small class="d-block text-muted mt-1">{{ ucfirst(str_replace('_', ' ', $activity['event_type'] ?? 'activity')) }}</small>
                                        </td>
                                        <td>{{ $activity['device'] ?: 'Unknown' }}</td>
                                        <td><span class="text-nowrap">{{ $activity['ip_address'] ?: '—' }}</span></td>
                                        <td>
                                            <?php if (isset($activity['latitude'], $activity['longitude'])): ?>
                                                <a class="dashboard-audit-location" href="https://www.google.com/maps?q={{ $activity['latitude'] }},{{ $activity['longitude'] }}" target="_blank" rel="noopener noreferrer"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ number_format((float) $activity['latitude'], 6) }}, {{ number_format((float) $activity['longitude'], 6) }}</a>
                                                <span class="dashboard-audit-location-meta">
                                                    <?php if (!empty($activity['location_accuracy'])): ?>Accuracy ±{{ number_format((float) $activity['location_accuracy'], 0) }} m<?php endif; ?>
                                                    <?php if (!empty($activity['location_captured_at'])): ?>{{ !empty($activity['location_accuracy']) ? ' · ' : '' }}Captured {{ $activity['location_captured_at'] }}<?php endif; ?>
                                                </span>
                                            <?php elseif (!empty($activity['city']) || !empty($activity['country'])): ?>
                                                <span>{{ trim(($activity['city'] ?? '') . ', ' . ($activity['country'] ?? ''), ', ') }}</span>
                                                <span class="dashboard-audit-location-meta">Approximate location</span>
                                            <?php else: ?>
                                                <span class="text-muted">Location not shared</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="text-nowrap">{{ $activity['time'] ?? 'Time not recorded' }}</span></td>
                                        <td>
                                            <?php if ($role === 'admin' && \Illuminate\Support\Facades\Route::has('admin.user-activity.show')): ?>
                                                <a class="dashboard-user-view-activity" href="{{ route('admin.user-activity.show', ['user' => $activity['user_id'] ?? 0]) }}"><i class="fa fa-history" aria-hidden="true"></i> View History</a>
                                            <?php elseif ($role === 'driver'): ?>
                                                <span class="dashboard-user-badge">My record</span>
                                            <?php else: ?>
                                                —
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="dashboard-report-empty">
                                            <i class="fa fa-history fa-2x text-muted mb-3" aria-hidden="true"></i>
                                            <strong class="d-block">No activity records found</strong>
                                            <span>Try changing the search, date range or location filter.</span>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php if ($role === 'driver'): ?>
                <div class="dashboard-own-activity-note">Your records are private to your driver view. Precise coordinates appear only when the location was explicitly shared and saved in UserHistory.</div>
            <?php else: ?>
                <div class="dashboard-data-note"><i class="fa fa-info-circle" aria-hidden="true"></i><span>This table displays up to 100 most recent matching UserHistory records in the selected dashboard reporting period. Precise coordinates appear only when they were explicitly shared and saved.</span></div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<?php if (in_array($role, ['admin', 'operations', 'accountant'], true)): ?>
    @php
        $userDirectoryResetQuery = request()->except([
            'user_q', 'user_role', 'user_status', 'user_registered_from',
            'user_registered_to', 'user_activity_period', 'page', 'user_page',
        ]);
    @endphp
    <section class="dashboard-widget-panel dashboard-widget-panel-full dashboard-user-directory-panel" aria-labelledby="dashboard-user-directory-title">
        <div class="dashboard-widget-header">
            <div class="dashboard-widget-heading">
                <span class="dashboard-widget-heading-icon is-success" aria-hidden="true"><i class="fa fa-users"></i></span>
                <div>
                    <h3 class="dashboard-widget-title" id="dashboard-user-directory-title">User directory & activity</h3>
                    <p class="dashboard-widget-description">Search and filter basic user details. Detailed per-user audit history is available to Admin only.</p>
                </div>
            </div>
            <span class="dashboard-widget-total">{{ $userDirectory && method_exists($userDirectory, 'total') ? number_format($userDirectory->total()) : '0' }} users</span>
            <?php if (\Illuminate\Support\Facades\Route::has('admin.user-activity.index')): ?>
                <a class="dashboard-user-view-activity" href="{{ route('admin.user-activity.index') }}">Open full directory →</a>
            <?php endif; ?>
        </div>

        <div class="dashboard-widget-body">
            <div class="dashboard-user-directory">
                <div class="dashboard-report-filter-card">
                    <h4 class="dashboard-report-filter-heading">User Directory Filters</h4>
                    <form class="dashboard-user-filter" action="{{ request()->url() }}" method="GET" aria-label="Filter user directory">
                        <?php foreach (request()->query() as $queryKey => $queryValue): ?>
                            <?php if (!in_array($queryKey, ['user_q', 'user_role', 'user_status', 'user_registered_from', 'user_registered_to', 'user_activity_period', 'page', 'user_page'], true) && is_scalar($queryValue)): ?>
                                <input type="hidden" name="{{ $queryKey }}" value="{{ $queryValue }}">
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <div class="dashboard-user-filter-field dashboard-user-search-field">
                            <label for="user-directory-search">Search</label>
                            <input id="user-directory-search" class="dashboard-user-search" type="search" name="user_q" value="{{ request('user_q') }}" placeholder="Name, email or phone">
                        </div>

                        <div class="dashboard-user-filter-field">
                            <label for="user-directory-role">Role</label>
                            <select id="user-directory-role" name="user_role" class="form-control">
                                <option value="">All roles</option>
                                <?php foreach (['admin' => 'Admin', 'operations' => 'Operations', 'accountant' => 'Accountant', 'driver' => 'Driver'] as $roleValue => $roleLabel): ?>
                                    <option value="{{ $roleValue }}" {{ request('user_role') === $roleValue ? 'selected' : '' }}>{{ $roleLabel }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="dashboard-user-filter-field">
                            <label for="user-directory-status">Status</label>
                            <select id="user-directory-status" name="user_status" class="form-control">
                                <option value="">All account statuses</option>
                                <option value="active" {{ (request('user_status') === 'active') ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ (request('user_status') === 'inactive') ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="dashboard-user-filter-field">
                            <label for="user-directory-activity">Activity Log</label>
                            <select id="user-directory-activity" name="user_activity_period" class="form-control">
                                <option value="">Any activity</option>
                                <option value="has_activity" {{ (request('user_activity_period') === 'has_activity') ? 'selected' : '' }}>Has activity log</option>
                                <option value="no_activity" {{ (request('user_activity_period') === 'no_activity') ? 'selected' : '' }}>No activity log</option>
                            </select>
                        </div>

                        <div class="dashboard-user-filter-field">
                            <label for="user-registered-from">Registered From</label>
                            <input id="user-registered-from" type="date" name="user_registered_from" value="{{ request('user_registered_from') }}" class="form-control">
                        </div>

                        <div class="dashboard-user-filter-field">
                            <label for="user-registered-to">Registered To</label>
                            <input id="user-registered-to" type="date" name="user_registered_to" value="{{ request('user_registered_to') }}" class="form-control">
                        </div>

                        <div class="dashboard-user-filter-actions">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
                            <a href="{{ request()->url() }}{{ count($userDirectoryResetQuery) ? '?' . http_build_query($userDirectoryResetQuery) : '' }}" class="btn btn-secondary"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</a>
                        </div>
                    </form>
                </div>

                <div class="card-box mb-30 dashboard-report-table-card">
                    <div class="pd-20">
                        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                            <h4 class="dashboard-report-table-title">All Users</h4>
                            <span class="badge badge-primary">Total: {{ $userDirectory && method_exists($userDirectory, 'total') ? number_format($userDirectory->total()) : '0' }}</span>
                        </div>
                    </div>
                    <div class="pb-20">
                        <div class="table-responsive">
                            <table class="table hover nowrap p-3 dashboard-user-table" data-title="User Directory & Activity">
                                <thead>
                                    <tr>
                                        <th>User</th><th>Phone</th><th>Role</th><th>Status</th><th>Registered</th><th>Last Activity</th><th>Logs</th><th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($userDirectory && $userDirectory->count()): ?>
                                        <?php foreach ($userDirectory as $directoryUser): ?>
                                            <tr>
                                                <td>
                                                    <div class="dashboard-user-identity">
                                                        <span class="dashboard-user-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($directoryUser->name ?: 'U', 0, 1)) }}</span>
                                                        <span>
                                                            <span class="dashboard-user-name">{{ $directoryUser->name ?: 'Unnamed user' }}</span>
                                                            <span class="dashboard-user-email">{{ $directoryUser->email ?: 'No email on record' }}</span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>{{ $directoryUser->phone ?: '—' }}</td>
                                                <td><span class="dashboard-user-badge">{{ str_replace('_', ' ', $directoryUser->role ?: 'unassigned') }}</span></td>
                                                <td><span class="dashboard-user-badge {{ $directoryUser->status ? 'is-active' : 'is-inactive' }}">{{ $directoryUser->status ? 'Active' : 'Inactive' }}</span></td>
                                                <td><span class="text-nowrap">{{ $directoryUser->created_at?->format('d M Y') ?: '—' }}</span></td>
                                                <td>{{ $directoryUser->histories_max_activity_time ? \Illuminate\Support\Carbon::parse($directoryUser->histories_max_activity_time)->format('d M Y, h:i A') : 'No activity logged' }}</td>
                                                <td>{{ number_format((int) ($directoryUser->histories_count ?? 0)) }}</td>
                                                <td>
                                                    <?php if ($role === 'admin' && \Illuminate\Support\Facades\Route::has('admin.user-activity.show')): ?>
                                                        <a class="dashboard-user-view-activity" href="{{ route('admin.user-activity.show', ['user' => $directoryUser->id]) }}"><i class="fa fa-history" aria-hidden="true"></i> View Activity</a>
                                                    <?php else: ?>
                                                        <button
                                                            type="button"
                                                            class="dashboard-basic-details-button"
                                                            data-basic-details-toggle="basic-details-{{ $directoryUser->id }}"
                                                            aria-controls="basic-details-{{ $directoryUser->id }}"
                                                            aria-expanded="false"
                                                        >
                                                            <i class="fa fa-id-card-o" aria-hidden="true"></i>
                                                            Basic details
                                                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php if (!($role === 'admin' && \Illuminate\Support\Facades\Route::has('admin.user-activity.show'))): ?>
                                                <tr id="basic-details-{{ $directoryUser->id }}" class="dashboard-basic-details-row" hidden>
                                                    <td colspan="8">
                                                        <div class="dashboard-basic-details-panel">
                                                            <div class="dashboard-basic-details-item">
                                                                <span class="dashboard-basic-details-label">User ID</span>
                                                                <span class="dashboard-basic-details-value">#{{ $directoryUser->id }}</span>
                                                            </div>
                                                            <div class="dashboard-basic-details-item">
                                                                <span class="dashboard-basic-details-label">Full name</span>
                                                                <span class="dashboard-basic-details-value">{{ $directoryUser->name ?: 'Unnamed user' }}</span>
                                                            </div>
                                                            <div class="dashboard-basic-details-item">
                                                                <span class="dashboard-basic-details-label">Email address</span>
                                                                <span class="dashboard-basic-details-value">{{ $directoryUser->email ?: 'Not provided' }}</span>
                                                            </div>
                                                            <div class="dashboard-basic-details-item">
                                                                <span class="dashboard-basic-details-label">Phone</span>
                                                                <span class="dashboard-basic-details-value">{{ $directoryUser->phone ?: 'Not provided' }}</span>
                                                            </div>
                                                            <div class="dashboard-basic-details-item">
                                                                <span class="dashboard-basic-details-label">Role</span>
                                                                <span class="dashboard-basic-details-value">{{ ucfirst(str_replace('_', ' ', $directoryUser->role ?: 'unassigned')) }}</span>
                                                            </div>
                                                            <div class="dashboard-basic-details-item">
                                                                <span class="dashboard-basic-details-label">Account status</span>
                                                                <span class="dashboard-basic-details-value">{{ $directoryUser->status ? 'Active' : 'Inactive' }}</span>
                                                            </div>
                                                            <div class="dashboard-basic-details-item">
                                                                <span class="dashboard-basic-details-label">Registered on</span>
                                                                <span class="dashboard-basic-details-value">{{ $directoryUser->created_at?->format('d M Y, h:i A') ?: 'Not available' }}</span>
                                                            </div>
                                                            <div class="dashboard-basic-details-item">
                                                                <span class="dashboard-basic-details-label">Last activity</span>
                                                                <span class="dashboard-basic-details-value">{{ $directoryUser->histories_max_activity_time ? \Illuminate\Support\Carbon::parse($directoryUser->histories_max_activity_time)->format('d M Y, h:i A') : 'No activity logged' }}</span>
                                                            </div>
                                                            <div class="dashboard-basic-details-item">
                                                                <span class="dashboard-basic-details-label">Activity logs</span>
                                                                <span class="dashboard-basic-details-value">{{ number_format((int) ($directoryUser->histories_count ?? 0)) }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="8" class="dashboard-report-empty"><i class="fa fa-users fa-2x text-muted mb-3" aria-hidden="true"></i><strong class="d-block">No users match these filters</strong><span>Try removing a filter or searching by name, email or phone.</span></td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if ($userDirectory && method_exists($userDirectory, 'links')): ?>
                        <div class="dashboard-user-pagination">
                            <span>Showing {{ $userDirectory->firstItem() ?: 0 }}–{{ $userDirectory->lastItem() ?: 0 }} of {{ $userDirectory->total() }} users</span>
                            {{ $userDirectory->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<div class="dashboard-access-note" role="note">
    <i class="fa fa-info-circle" aria-hidden="true"></i>
    <div>
        <strong>Your dashboard, organised by workflow.</strong>
        Use search to find a metric and filter the visible business cards. The workflow cards stay expanded for quick scanning.
        Values come from the DashboardService. Cards without a configured module route remain informational.
    </div>
</div>


<script>
(function () {
    'use strict';

    var root = document.getElementById('dashboard-sections-list');
    var searchInput = document.getElementById('dashboard-metric-search');
    var clearButton = document.getElementById('dashboard-search-clear');
    var resultCount = document.getElementById('dashboard-results-count');
    var noResults = document.getElementById('dashboard-no-results');
    var resetButton = document.getElementById('dashboard-reset-filters');

    if (!root || !searchInput || root.getAttribute('data-dashboard-ui-ready') === 'true') {
        return;
    }
    root.setAttribute('data-dashboard-ui-ready', 'true');

    var cards = Array.prototype.slice.call(root.querySelectorAll('[data-dashboard-card]'));
    var sections = Array.prototype.slice.call(root.querySelectorAll('[data-dashboard-section]'));
    var filterButtons = Array.prototype.slice.call(document.querySelectorAll('[data-dashboard-filter]'));
    var activeFilter = 'all';
    var totalCards = cards.length;

    // Avoid showing a misleading "no results" message for a role that has no configured cards.
    if (totalCards === 0) {
        if (noResults) noResults.hidden = true;
        if (searchInput) searchInput.disabled = true;
        filterButtons.forEach(function (button) { button.disabled = true; });
        if (clearButton) clearButton.disabled = true;
        return;
    }

    function updateDashboard() {
        var query = (searchInput.value || '').toLowerCase().trim();
        var visibleCards = 0;
        var visibleSections = 0;

        cards.forEach(function (card) {
            var matchesSearch = !query || (card.getAttribute('data-search') || '').indexOf(query) !== -1;
            var matchesFilter = activeFilter === 'all' ||
                (activeFilter === 'attention' && card.getAttribute('data-attention') === 'true') ||
                (activeFilter === 'linked' && card.getAttribute('data-linked') === 'true');
            var isVisible = matchesSearch && matchesFilter;

            card.hidden = !isVisible;
            if (isVisible) visibleCards++;
        });

        sections.forEach(function (section) {
            var sectionCards = Array.prototype.slice.call(section.querySelectorAll('[data-dashboard-card]'));
            var sectionVisible = sectionCards.filter(function (card) { return !card.hidden; }).length;
            var emptyMessage = section.querySelector('[data-section-empty]');
            var count = section.querySelector('[data-section-visible-count]');

            section.hidden = sectionVisible === 0;
            if (emptyMessage) emptyMessage.hidden = sectionVisible !== 0 || (!query && activeFilter === 'all');
            if (count) count.textContent = String(sectionVisible);

            if (sectionVisible > 0) {
                visibleSections++;
                if (query || activeFilter !== 'all') section.open = true;
            }
        });

        if (resultCount) {
            resultCount.innerHTML = 'Showing <strong>' + visibleCards.toLocaleString() +
                '</strong> of <strong>' + totalCards.toLocaleString() + '</strong> metrics';
        }
        if (noResults) noResults.hidden = visibleCards !== 0;
        if (clearButton) clearButton.style.display = searchInput.value ? 'inline-flex' : 'none';
    }

    filterButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            activeFilter = button.getAttribute('data-dashboard-filter') || 'all';
            filterButtons.forEach(function (candidate) {
                var isActive = candidate === button;
                candidate.classList.toggle('is-active', isActive);
                candidate.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
            updateDashboard();
        });
    });

    searchInput.addEventListener('input', updateDashboard);

    if (clearButton) {
        clearButton.addEventListener('click', function () {
            searchInput.value = '';
            searchInput.focus();
            updateDashboard();
        });
    }

    function resetDashboardFilters() {
        searchInput.value = '';
        activeFilter = 'all';
        filterButtons.forEach(function (button) {
            var isAll = button.getAttribute('data-dashboard-filter') === 'all';
            button.classList.toggle('is-active', isAll);
            button.setAttribute('aria-pressed', isAll ? 'true' : 'false');
        });
        updateDashboard();
    }

    if (resetButton) resetButton.addEventListener('click', resetDashboardFilters);

    // Clicking a workflow bar scrolls to its always-visible matching metric section.
    Array.prototype.slice.call(document.querySelectorAll('[data-jump-section]')).forEach(function (button) {
        button.addEventListener('click', function () {
            var slug = button.getAttribute('data-jump-section');
            var target = sections.find(function (section) {
                var heading = section.querySelector('.dashboard-section-heading h3');
                return heading && heading.textContent.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-') .replace(/^-|-$/g, '') === slug;
            });
            if (target) {
                target.open = true;
                target.scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' });
                target.style.boxShadow = '0 0 0 2px rgba(59,130,246,.22)';
                window.setTimeout(function () { target.style.boxShadow = ''; }, 1200);
            }
        });
    });

    // Reporting-range controls only affect dated aggregates supplied by the backend.
    var periodSelect = document.getElementById('dashboard-period-select');
    var startDate = document.getElementById('dashboard-start-date');
    var endDate = document.getElementById('dashboard-end-date');
    var dateFilterForm = document.getElementById('dashboard-date-filter-form');
    if (periodSelect && startDate && endDate) {
        function updateCustomDateControls() {
            var isCustom = periodSelect.value === 'custom';
            startDate.disabled = !isCustom;
            endDate.disabled = !isCustom;
            startDate.required = isCustom;
            endDate.required = isCustom;
        }
        periodSelect.addEventListener('change', function () {
            updateCustomDateControls();
            if (periodSelect.value !== 'custom' && dateFilterForm) dateFilterForm.submit();
        });
        if (dateFilterForm) {
            dateFilterForm.addEventListener('submit', function (event) {
                if (periodSelect.value === 'custom' && startDate.value && endDate.value && startDate.value > endDate.value) {
                    event.preventDefault();
                    window.alert('Start date must be on or before the end date.');
                }
            });
        }
        updateCustomDateControls();
    }

    // CSV is generated only from metrics currently visible to this authenticated role.
    var exportButton = document.getElementById('dashboard-export-csv');
    if (exportButton) {
        exportButton.addEventListener('click', function () {
            var visibleCards = Array.prototype.slice.call(document.querySelectorAll('[data-dashboard-card]')).filter(function (card) {
                return !card.hidden;
            });
            if (!visibleCards.length) {
                window.alert('There are no visible metric cards to export.');
                return;
            }
            function csvCell(value) {
                var text = String(value == null ? '' : value).replace(/\s+/g, ' ').trim();
                if (/^[=+\-@]/.test(text)) text = "'" + text;
                return '"' + text.replace(/"/g, '""') + '"';
            }
            var rows = [['Metric', 'Value', 'Description', 'Status', 'Action']];
            visibleCards.forEach(function (card) {
                var title = card.querySelector('.premium-stat-label');
                var value = card.querySelector('.premium-stat-value');
                var subtitle = card.querySelector('.premium-stat-subtitle');
                var status = card.querySelector('.premium-stat-status');
                var link = card.querySelector('.premium-stat-link');
                rows.push([
                    title ? title.textContent : '',
                    value ? value.textContent : '',
                    subtitle ? subtitle.textContent : '',
                    status ? status.textContent : '',
                    link ? link.textContent : 'Informational only'
                ]);
            });
            var csv = '\ufeff' + rows.map(function (row) { return row.map(csvCell).join(','); }).join('\r\n');
            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            var url = window.URL.createObjectURL(blob);
            var link = document.createElement('a');
            link.href = url;
            link.download = 'dashboard-metrics-' + new Date().toISOString().slice(0, 10) + '.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
        });
    }

    var printButton = document.getElementById('dashboard-print-pdf');
    if (printButton) printButton.addEventListener('click', function () { window.print(); });

    // Expand/collapse basic user details in the current table without another route or controller.
    document.addEventListener('click', function (event) {
        var button = event.target.closest('[data-basic-details-toggle]');
        if (!button) return;

        var detailsId = button.getAttribute('data-basic-details-toggle');
        var detailsRow = detailsId ? document.getElementById(detailsId) : null;
        if (!detailsRow) return;

        var isOpening = detailsRow.hidden;
        detailsRow.hidden = !isOpening;
        button.setAttribute('aria-expanded', isOpening ? 'true' : 'false');

        var chevron = button.querySelector('.fa-chevron-down, .fa-chevron-up');
        if (chevron) {
            chevron.classList.toggle('fa-chevron-down', !isOpening);
            chevron.classList.toggle('fa-chevron-up', isOpening);
        }

        if (isOpening) {
            detailsRow.scrollIntoView({
                behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
                block: 'nearest'
            });
        }
    });

    updateDashboard();
}());
</script>
