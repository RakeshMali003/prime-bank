
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

