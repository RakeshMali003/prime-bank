<<<<<<< HEAD
<section class="process-section">
    <div class="container">
        <div class="text-center mb-5" style="max-width: 700px; margin: 0 auto;">
            <p style="color: #3b82f6; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">How it Works</p>
            <h2 style="font-weight: 800; color: #0f172a; font-size: 2.5rem;">Join Us in 5 Simple Steps</h2>
            <p style="color: #64748b;">We have simplified the banking process to get you started in minutes, not days.</p>
        </div>

        <div class="process-timeline">
            
            <div class="step-item reveal-step" style="transition-delay: 0s;">
                <div class="step-icon-box">
                    <div class="step-badge">1</div>
                    <img src=".\photo\first-steps.png" alt="Sign In">
                </div>
                <div class="step-content">
                    <h4>Sign In</h4>
                    <p>Unlock possibilities with a single click. Create your secure ID.</p>
                </div>
            </div>

            <div class="step-item reveal-step" style="transition-delay: 0.1s;">
                <div class="step-icon-box">
                    <div class="step-badge">2</div>
                    <img src=".\photo\next.png" alt="Open Account">
                </div>
                <div class="step-content">
                    <h4>Open Account</h4>
                    <p>Complete the digital application form to become a holder.</p>
                </div>
            </div>

            <div class="step-item reveal-step" style="transition-delay: 0.2s;">
                <div class="step-icon-box">
                    <div class="step-badge">3</div>
                    <img src=".\photo\next.png" alt="Verification">
                </div>
                <div class="step-content">
                    <h4>Verification</h4>
                    <p>Verify your Email & Mobile securely via OTP.</p>
                </div>
            </div>

            <div class="step-item reveal-step" style="transition-delay: 0.3s;">
                <div class="step-icon-box">
                    <div class="step-badge">4</div>
                    <img src=".\photo\next.png" alt="Deposit">
                </div>
                <div class="step-content">
                    <h4>Deposit Funds</h4>
                    <p>Add funds instantly to activate plans like FDR or DPS.</p>
                </div>
            </div>

            <div class="step-item reveal-step" style="transition-delay: 0.4s;">
                <div class="step-icon-box">
                    <div class="step-badge">5</div>
                    <img src=".\photo\checked.png" alt="Get Service">
                </div>
                <div class="step-content">
                    <h4>Get Service</h4>
                    <p>You are now ready to enjoy our premium banking services.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const steps = document.querySelectorAll('.reveal-step');

        const observerOptions = {
            threshold: 0.2, // Trigger when 20% of the item is visible
            rootMargin: "0px 0px -50px 0px"
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible-step');
                    // Optional: Stop observing once visible so it doesn't animate out
                    observer.unobserve(entry.target); 
                }
            });
        }, observerOptions);

        steps.forEach(step => {
            observer.observe(step);
        });
    });
</script>
=======

    <p class="text-center">How it works</p>
    <h1>It's easy to join with Us</h1>
    <div class="steps-container">
        <div class="step hidden-step">
            <div class="step-number">1</div>
            <img src="..\BANK ONLINE PROJECT\photo\first-steps.png" style="height:100px; width:100px" alt="Sign In">
            
            <div class="step-description">Sign In</div>
            <p>Unlock a world of possibilities with a single click – Sign in and explore!</p>
        </div>
        <div class="step hidden-step">
            <div class="step-number">2</div>
            <img src="..\BANK ONLINE PROJECT\photo\next.png" style="height:100px; width:100px" alt="Open Account">
            <div class="step-description">Open an Account</div>
            <p>To be an account holder you have to open an account first.</p>
        </div>
        <div class="step hidden-step">
            <div class="step-number">3</div>
            <img src="..\BANK ONLINE PROJECT\photo\next.png" style="height:100px; width:100px" alt="Verification">
            <div class="step-description">Verification</div>
            <p>After registration you need to verify your Email and Mobile Number.</p>
        </div>
        <div class="step hidden-step">
            <div class="step-number">4</div>
            <img src="..\BANK ONLINE PROJECT\photo\next.png" style="height:100px; width:100px" alt="Deposit">
            <div class="step-description">Deposit</div>
            <p>Deposit some funds before applying on any FDR or DPS plans.</p>
        </div>
        <div class="step hidden-step">
            <div class="step-number">5</div>
            <img src="..\BANK ONLINE PROJECT\photo\checked.png" style="height:100px; width:100px" alt="Get Service">
            <div class="step-description">Get Service</div>
            <p>Now you can get any of our services as our registered account-holder</p>
        </div>
    </div>

    
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const steps = document.querySelectorAll('.step');

            function checkScroll() {
                steps.forEach(step => {
                    const rect = step.getBoundingClientRect();
                    const isVisible = (rect.top >= 0 && rect.bottom <= window.innerHeight);
                    
                    if (isVisible) {
                        step.classList.add('visible-step');
                    
                    } else {
                    
                        step.classList.remove('visible-step');
                    }
                });
            }

            window.addEventListener('scroll', checkScroll);
            checkScroll(); // Initial check
        });
    </script>

>>>>>>> 75138d4784e452aef7ef999cabc36ef03d0f92ec
