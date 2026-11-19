<footer class="mastermind-footer">

    <div class="mastermind-footer-inner">

        {{-- Copyright Section --}}
        <div class="footer-copyright">

            <span class="footer-copyright-icon">
                <i class="fa fa-copyright"></i>
            </span>

            <span>
                {{ date('Y') }}

                <a
                    href="https://mastermindtravels.net/"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="footer-link"
                    aria-label="Visit Mastermind Travels website"
                >
                    Mastermind Travels
                </a>

                <span class="footer-rights">
                    . All Rights Reserved.
                </span>
            </span>

        </div>


        {{-- Developer Credit Section --}}
        <div class="footer-developer">

            <span class="footer-developed-text">
                Designed &amp; Developed with
            </span>

            <span class="footer-heart" aria-label="love">
                <i class="fa fa-heart" aria-hidden="true"></i>
            </span>

            <span class="footer-developed-text">
                by
            </span>

            <a
                href="https://pixelpearltechnologies.com/"
                target="_blank"
                rel="noopener noreferrer"
                class="footer-link developer-link"
                aria-label="Visit PixelPearl Technologies LLP website"
            >
                PixelPearl Technologies LLP
                <i class="fa fa-external-link footer-external-icon"></i>
            </a>

        </div>

    </div>

</footer>


<style>

/* ================================================================
   MASTERMIND FOOTER - PROFESSIONAL UI
================================================================ */

.mastermind-footer {
    position: relative;
    width: 100%;
    margin: 25px 0 20px;
    padding: 0;
    border: 1px solid #e6edf6;
    border-radius: 13px;
    background: linear-gradient(110deg, #ffffff 0%, #f7faff 100%);
    box-shadow: 0 4px 16px rgba(26, 47, 84, .045);
    overflow: hidden;
}


/* Top Accent Line */

.mastermind-footer::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(
        90deg,
        #063573 0%,
        #3978d4 50%,
        #063573 100%
    );
    content: "";
}


/* Footer Inner Layout */

.mastermind-footer-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 17px 23px;
}


/* Copyright Section */

.footer-copyright {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    color: #667085;
    font-size: 13px;
    line-height: 1.7;
}


/* Copyright Icon */

.footer-copyright-icon {
    display: inline-flex;
    width: 28px;
    height: 28px;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #edf4ff;
    color: #063573;
    font-size: 12px;
}


/* Copyright Text */

.footer-rights {
    color: #98a2b3;
}


/* Developer Credit */

.footer-developer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 5px;
    color: #667085;
    font-size: 12px;
    line-height: 1.7;
}

.footer-developed-text {
    color: #718096;
}


/* Footer Links */

.mastermind-footer .footer-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #063573;
    font-weight: 750;
    text-decoration: none;
    transition: color .2s ease;
}

.mastermind-footer .footer-link:hover {
    color: #3978d4;
    text-decoration: underline;
    text-underline-offset: 3px;
}

.mastermind-footer .footer-link:focus-visible {
    outline: 2px solid #3978d4;
    outline-offset: 3px;
    border-radius: 3px;
}


/* Developer Link */

.mastermind-footer .developer-link {
    font-weight: 750;
}

.footer-external-icon {
    font-size: 9px;
    opacity: .55;
    transition: transform .2s ease, opacity .2s ease;
}

.mastermind-footer .developer-link:hover .footer-external-icon {
    transform: translate(2px, -2px);
    opacity: 1;
}


/* Heart Animation */

.footer-heart {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    color: #dc3545;
    font-size: 12px;
    animation: mastermind-heartbeat 1.8s ease-in-out infinite;
}

@keyframes mastermind-heartbeat {

    0%, 100% {
        transform: scale(1);
    }

    15% {
        transform: scale(1.18);
    }

    30% {
        transform: scale(1);
    }

    45% {
        transform: scale(1.18);
    }

    60% {
        transform: scale(1);
    }

}


/* ================================================================
   RESPONSIVE DESIGN
================================================================ */

@media (max-width: 767.98px) {

    .mastermind-footer {
        margin-top: 20px;
        margin-bottom: 15px;
        border-radius: 11px;
    }

    .mastermind-footer-inner {
        flex-direction: column;
        justify-content: center;
        gap: 9px;
        padding: 17px 14px;
        text-align: center;
    }

    .footer-copyright,
    .footer-developer {
        justify-content: center;
        font-size: 11px;
        text-align: center;
    }

    .footer-copyright-icon {
        width: 25px;
        height: 25px;
    }

}

@media (max-width: 380px) {

    .mastermind-footer-inner {
        padding: 15px 10px;
    }

    .footer-copyright,
    .footer-developer {
        font-size: 10px;
    }

}


/* Respect reduced-motion preferences */

@media (prefers-reduced-motion: reduce) {

    .footer-heart {
        animation: none;
    }

    .mastermind-footer .footer-link,
    .footer-external-icon {
        transition: none;
    }

}

</style>
