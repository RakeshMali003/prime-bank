<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prime Bank | Secure Global Banking</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#0B0F19',
                            blue: '#1E40AF',
                            accent: '#38BDF8',
                            gold: '#F59E0B',
                            glass: 'rgba(255, 255, 255, 0.05)'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #0B0F19;
            color: #E2E8F0;
            overflow-x: hidden;
        }

        /* Abstract Background Animation */
        .bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.4;
            animation: float 10s infinite alternate;
        }
        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(30px, 50px); }
        }

        /* Glassmorphism Utilities */
        .glass-panel {
            background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .glass-nav {
            background: rgba(11, 15, 25, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
          /* 3D ATM Card Styling */
        .atm-card {
            background: linear-gradient(135deg, #FFD700 0%, #B8860B 100%);
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(184, 134, 11, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .atm-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0) 40%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 60%);
            z-index: 1;
        }

        /* Ticker Animation */
        @keyframes ticker {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .ticker-wrap { overflow: hidden; white-space: nowrap; }
        .ticker-move { display: inline-block; animation: ticker 30s linear infinite; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0B0F19; }
        ::-webkit-scrollbar-thumb { background: #1E40AF; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="bg-orb w-96 h-96 bg-blue-600 top-0 left-0"></div>
    <div class="bg-orb w-96 h-96 bg-purple-600 bottom-0 right-0"></div>
    <div class="bg-orb w-64 h-64 bg-cyan-500 top-1/2 left-1/2 transform -translate-x-1/2"></div>

    <div class="bg-brand-blue/20 border-b border-white/5 text-xs py-2">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex gap-4">
                <span class="text-brand-accent font-bold"><i class="ri-notification-3-line"></i> UPDATES:</span>
                <div class="ticker-wrap w-64 md:w-96">
                    <div class="ticker-move text-gray-300">
                        New Home Loan Interest Rates starting @ 8.40% p.a. | Beware of KYC Fraud Calls | RBI Digital Rupee Pilot Launched | Download our new Secured App
                    </div>
                </div>
            </div>
            <div class="flex gap-4 items-center">
                <button class="hover:text-white"><i class="ri-global-line"></i> EN/HI</button>
                <button class="hover:text-white"><i class="ri-wheelchair-line"></i> Accessibility</button>
                <a href="#contact" class="hover:text-white"><i class="ri-phone-line"></i> Support</a>
            </div>
        </div>
    </div>

    <nav class="glass-nav sticky top-0 z-50 py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
        
                <a class="navbar-brand" href=".\index.php"><img src=".\weblogo.png" style="height:60px; width:100px;"></a>  
              
          

            <div class="hidden lg:flex gap-8 text-sm font-medium text-gray-300">
                <a href="#about" class="hover:text-brand-accent transition">About</a>
                <a href="#services" class="hover:text-brand-accent transition">Services</a>
                <a href="#digital" class="hover:text-brand-accent transition">Digital</a>
                <a href="#loans" class="hover:text-brand-accent transition">Loans</a>
                <a href="#security" class="hover:text-brand-accent transition">Security</a>
            </div>

      <div class="flex gap-3">
    <!-- Register Button -->
    <a href=".\common user interface\userregistration.php"
        class="hidden md:block px-4 py-2 text-sm border border-gray-600 rounded-full hover:border-white transition">
        Register
    </a>

    <!-- Login Button -->
    <a href=".\common user interface\userregistration.php"
        class="px-5 py-2 text-sm bg-brand-blue hover:bg-blue-600 text-white font-bold rounded-full shadow-lg shadow-blue-900/50 transition">
        <i class="ri-lock-line"></i> Login
    </a>

    <!-- Menu Button (No Redirect) -->
    <button class="lg:hidden text-2xl">
        <i class="ri-menu-line"></i>
    </button>
</div>


        </div>
    </nav>

    <header class="relative pt-20 pb-32 overflow-hidden">
        <div class="container mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="hero-content">
                <div class="inline-block px-3 py-1 mb-4 text-xs font-bold tracking-widest text-brand-gold border border-brand-gold/30 rounded-full bg-brand-gold/10">
                    SECURE & ROBUST E-BANKING
                </div>
                <h1 class="text-5xl md:text-7xl font-serif font-bold leading-tight mb-6">
                    Banking for the <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-blue">Global Era.</span>
                </h1>
                <p class="text-gray-400 text-lg mb-8 max-w-lg">
                    We care about your money and safety. Join Prime Bank for secure, fast, and easy financial growth with domain knowledge-based human resources.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href=".\common user interface\userregistration.php" class="px-8 py-4 bg-white text-brand-dark font-bold rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
                        Open Account <i class="ri-arrow-right-line"></i>
                    </a>

                   

                    <a href="#about" class="px-8 py-4 glass-panel text-white font-bold rounded-lg hover:bg-white/10 transition">
                        About Us
                    </a>
                </div>
            </div>
            

            <div class="flex justify-center perspective-1000">
                <div class="atm-card w-[380px] h-[240px] p-6 flex flex-col justify-between transform transition-transform duration-500 hover:scale-105" data-tilt data-tilt-glare data-tilt-max-glare="0.4">
                    <div class="flex justify-between items-start">
                        <div class="font-serif font-bold italic text-black/80 text-xl tracking-wider">PRIME BANK</div>
                        <i class="ri-wifi-line text-3xl text-black/70 rotate-90"></i>
                    </div>
                    
                    <div class="w-12 h-9 bg-yellow-200 rounded-md border border-yellow-400 relative overflow-hidden mt-2 opacity-80">
                        <div class="absolute w-full h-[1px] bg-yellow-500 top-1/2"></div>
                        <div class="absolute h-full w-[1px] bg-yellow-500 left-1/2"></div>
                    </div>

                    <div class="mt-4">
                        <div class="font-mono text-2xl font-bold text-black/90 tracking-widest" style="text-shadow: 0 1px 0 rgba(255,255,255,0.4);">
                            4512 7890 3456 1234
                        </div>
                    </div>

                    <div class="flex justify-between items-end mt-2">
                        <div>
                            <div class="text-[10px] uppercase font-bold text-black/60">Card Holder</div>
                            <div class="text-sm font-bold text-black/90 uppercase tracking-wide">RAKESH MALI</div>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-black/60 text-right">Expires</div>
                            <div class="text-sm font-bold text-black/90 text-right">09/28</div>
                        </div>
                        <div class="text-3xl font-bold text-blue-900 italic opacity-80">VISA</div>
                    </div>
                </div>
            </div>
    </header>

    <div class="border-y border-white/10 bg-black/20 backdrop-blur-sm stats-bar">
        <div class="container mx-auto px-6 py-8 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-3xl font-bold text-brand-accent">24/7</div>
                <div class="text-xs text-gray-500 uppercase tracking-wide">Banking Access</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-brand-accent">100%</div>
                <div class="text-xs text-gray-500 uppercase tracking-wide">Secure (2FA)</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-brand-accent">Low</div>
                <div class="text-xs text-gray-500 uppercase tracking-wide">Transaction Fee</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-brand-accent">Global</div>
                <div class="text-xs text-gray-500 uppercase tracking-wide">Service Reach</div>
            </div>
        </div>
    </div>

    <section id="about" class="py-20 relative overflow-hidden">
        <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div class="about-text">
                <h2 class="text-4xl font-serif font-bold mb-6">About <span class="text-brand-accent">Prime Bank</span></h2>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    Prime is a complete e-Banking system originating from <strong>Manipal University, Jaipur</strong>. We have grown to serve account-holders from almost all over the world. Our system is secure, robust, and designed for the modern era.
                </p>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    We care about your money and safety. We are focused on building and sustaining long-term generational relationships with our customers.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    <div class="glass-panel p-4 rounded-lg border-l-4 border-brand-accent">
                        <h4 class="font-bold text-white mb-1">Our Mission</h4>
                        <p class="text-xs text-gray-400">To build domain knowledge-based human resources by imparting contemporary technical skills and social values.</p>
                    </div>
                    <div class="glass-panel p-4 rounded-lg border-l-4 border-brand-gold">
                        <h4 class="font-bold text-white mb-1">Our Vision</h4>
                        <p class="text-xs text-gray-400">To provide domain knowledge-based human resources by imparting contemporary technical skills.</p>
                    </div>
                </div>
            </div>
           <div class="about-visual relative">
    <div class="absolute inset-0 bg-brand-blue/20 blur-3xl rounded-full"></div>

    <video autoplay loop muted playsinline class="relative rounded-2xl shadow-2xl opacity-90 border border-white/10 w-full h-full object-cover">
        <source src=".\photo\production_id_4428753 (2160p).mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

        </div>
    </section>

 <section id="services" class="py-24 relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-serif font-bold mb-4">Our Services</h2>
                <p class="text-gray-400">We make your life comfortable with our premium services.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="glass-panel p-8 rounded-2xl group text-center service-card">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl shadow-lg shadow-blue-600/50">
                        <i class="ri-flashlight-fill"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Fast Transfer</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Experience lightning-fast transfers with our seamless and secure transaction platform.</p>
                </div>

                <div class="glass-panel p-8 rounded-2xl group text-center service-card">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl shadow-lg shadow-green-600/50">
                        <i class="ri-download-2-fill"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Deposit Funds</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Effortlessly deposit funds into your account, ensuring a swift and hassle-free process.</p>
                </div>

                <div class="glass-panel p-8 rounded-2xl group text-center service-card">
                    <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl shadow-lg shadow-red-600/50">
                        <i class="ri-upload-2-fill"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Withdraw Funds</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Enjoy quick and convenient fund withdrawals providing you with instant access to your finances.</p>
                </div>

                <div class="glass-panel p-8 rounded-2xl group text-center service-card">
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl shadow-lg shadow-purple-600/50">
                        <i class="ri-shopping-cart-2-fill"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Online Payment</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Secure gateway for bills, shopping, and merchants. Verified by Visa/Mastercard SecureCode.</p>
                </div>

                <div class="glass-panel p-8 rounded-2xl group text-center service-card">
                    <div class="w-16 h-16 bg-brand-gold rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl shadow-lg shadow-yellow-600/50">
                        <i class="ri-hand-coin-fill"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Take Loan</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">We offer the best Personal, Home, and Car loans. Minimal paperwork and quick disbursement.</p>
                </div>

                <div class="glass-panel p-8 rounded-2xl group text-center service-card">
                    <div class="w-16 h-16 bg-cyan-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl shadow-lg shadow-cyan-600/50">
                        <i class="ri-pie-chart-2-fill"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Deposit Schemes</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Secure your future with FDR (Fixed Deposit) & DPS (Monthly Savings). High-interest rates guaranteed.</p>
                </div>

            </div>
        </div>
    </section>

    <section id="digital" class="py-20 bg-gradient-to-r from-brand-dark to-blue-900/10">
        <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div class="digital-content">
                <h2 class="text-3xl font-serif font-bold mb-4">Go Digital with Prime</h2>
                <p class="text-gray-400 mb-8">Download our premium mobile app. Manage your account, invest, and pay bills from the comfort of your home.</p>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-4 glass-panel p-4 rounded-lg">
                        <i class="ri-smartphone-line text-2xl text-brand-accent"></i>
                        <div>
                            <h4 class="font-bold">Mobile Banking</h4>
                            <p class="text-sm text-gray-500">Available on iOS and Android. Fingerprint login enabled.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 glass-panel p-4 rounded-lg">
                        <i class="ri-global-line text-2xl text-brand-accent"></i>
                        <div>
                            <h4 class="font-bold">Net Banking</h4>
                            <p class="text-sm text-gray-500">Detailed statements, checkbook requests, and tax payments.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button class="flex items-center gap-2 bg-white text-black px-4 py-2 rounded-lg font-bold hover:bg-gray-200 transition">
                        <i class="ri-apple-fill text-xl"></i> App Store
                    </button>
                    <button class="flex items-center gap-2 border border-gray-600 px-4 py-2 rounded-lg font-bold hover:border-white transition">
                        <i class="ri-google-play-fill text-xl"></i> Google Play
                    </button>
                </div>
            </div>
            
            <div class="digital-visual flex justify-center">
                <div class="w-64 h-[500px] border-8 border-gray-800 rounded-[3rem] bg-gray-900 shadow-2xl relative overflow-hidden transform hover:-translate-y-2 transition duration-500">
                    <div class="absolute top-0 w-full h-full bg-brand-blue p-4">
                        <div class="flex justify-between items-center text-white mt-8 mb-8">
                            <i class="ri-menu-2-line"></i>
                            <span class="font-bold">Prime Bank</span>
                            <i class="ri-user-3-line"></i>
                        </div>
                        <div class="text-center text-white mb-8">
                            <div class="text-sm opacity-70">Available Balance</div>
                            <div class="text-3xl font-bold">₹ 4,50,000</div>
                        </div>
                        <div class="bg-white rounded-t-3xl h-full p-4">
                            <div class="flex justify-between mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600"><i class="ri-send-plane-fill"></i></div>
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600"><i class="ri-add-line"></i></div>
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600"><i class="ri-bill-line"></i></div>
                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-600"><i class="ri-more-fill"></i></div>
                            </div>
                            <div class="space-y-2 text-black">
                                <div class="text-xs font-bold text-gray-500 mb-2">RECENT</div>
                                <div class="flex justify-between text-xs border-b pb-2"><span>Netflix</span> <span class="text-red-500">- ₹649</span></div>
                                <div class="flex justify-between text-xs border-b pb-2"><span>Interest</span> <span class="text-green-500">+ ₹1,200</span></div>
                                <div class="flex justify-between text-xs border-b pb-2"><span>UPI Transfer</span> <span class="text-red-500">- ₹500</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="loans" class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-4">Interest Rates & Plans</h2>
            <p class="text-center text-gray-400 mb-10">We offer the best FDR, DPS & Loan plans to our account holders.</p>
            
            <div class="overflow-x-auto glass-panel rounded-xl loan-table">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-brand-blue/30 text-brand-accent border-b border-gray-700">
                            <th class="p-4">Product Type</th>
                            <th class="p-4">Interest Rate (p.a.)</th>
                            <th class="p-4">Tenure</th>
                            <th class="p-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300 text-sm">
                        <tr class="border-b border-gray-700 hover:bg-white/5 transition">
                            <td class="p-4 font-bold flex items-center gap-2"><i class="ri-home-4-line text-brand-gold"></i> Home Loan</td>
                            <td class="p-4 text-green-400">8.35% - 9.50%</td>
                            <td class="p-4">Up to 30 Years</td>
                            <td class="p-4"><button class="text-brand-accent hover:text-white">Apply Now</button></td>
                        </tr>
                        <tr class="border-b border-gray-700 hover:bg-white/5 transition">
                            <td class="p-4 font-bold flex items-center gap-2"><i class="ri-car-line text-brand-gold"></i> Car Loan</td>
                            <td class="p-4 text-green-400">8.85% Onwards</td>
                            <td class="p-4">Up to 7 Years</td>
                            <td class="p-4"><button class="text-brand-accent hover:text-white">Apply Now</button></td>
                        </tr>
                        <tr class="border-b border-gray-700 hover:bg-white/5 transition">
                            <td class="p-4 font-bold flex items-center gap-2"><i class="ri-user-star-line text-brand-gold"></i> Personal Loan</td>
                            <td class="p-4 text-green-400">10.50% Onwards</td>
                            <td class="p-4">Up to 5 Years</td>
                            <td class="p-4"><button class="text-brand-accent hover:text-white">Apply Now</button></td>
                        </tr>
                        <tr class="hover:bg-white/5 transition">
                            <td class="p-4 font-bold flex items-center gap-2"><i class="ri-safe-2-line text-brand-gold"></i> Fixed Deposit (FD)</td>
                            <td class="p-4 text-green-400">7.25% (Senior Citizens 7.75%)</td>
                            <td class="p-4">1 - 10 Years</td>
                            <td class="p-4"><button class="text-brand-accent hover:text-white">Invest Now</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="security" class="py-20 bg-brand-dark">
        <div class="container mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
            <div class="security-text">
                <h2 class="text-4xl font-serif font-bold mb-6">Why Choose Us?</h2>
                <div class="space-y-8">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-600/20 flex items-center justify-center text-blue-400 shrink-0">
                            <i class="ri-shield-keyhole-fill text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Secure Service (2FA)</h3>
                            <p class="text-gray-400 text-sm">Every balance subtracting transactions need OTP verification so You can feel safe about your funds. Also, you can use the Google Authenticator app on your cellphone and enable 2FA security from the account menu.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-green-600/20 flex items-center justify-center text-green-400 shrink-0">
                            <i class="ri-percent-line text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Lowest Transaction Fee</h3>
                            <p class="text-gray-400 text-sm">Our transaction fee is much low comparing to other banks. You can deposit, transfer, and withdraw your funds with the lowest transaction charge. As our transfer system is secure and robust you can trust us.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="glass-panel p-8 rounded-2xl border border-blue-500/30 relative overflow-hidden security-card">
                <div class="absolute inset-0 bg-blue-500/5 animate-pulse"></div>
                <h3 class="text-lg font-bold mb-6 border-b border-gray-700 pb-2">Security Status</h3>
                <ul class="space-y-4 text-sm">
                    <li class="flex justify-between"><span>SSL Encryption</span> <span class="text-green-400">Active <i class="ri-check-circle-fill"></i></span></li>
                    <li class="flex justify-between"><span>Fraud Monitor</span> <span class="text-green-400">Scanning <i class="ri-radar-fill"></i></span></li>
                    <li class="flex justify-between"><span>Database</span> <span class="text-green-400">Secured <i class="ri-database-2-fill"></i></span></li>
                    <li class="mt-4 p-3 bg-yellow-500/10 text-yellow-500 rounded text-xs">
                        <i class="ri-alert-line"></i> Never share your OTP or Password with anyone. Prime Bank never calls to ask for these details.
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="py-20 relative">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-serif font-bold mb-2">How It Works</h2>
            <p class="text-gray-400 mb-12">Join Us in 5 Simple Steps. We have simplified the banking process.</p>
            
            <div class="flex flex-wrap justify-center gap-8 relative z-10 steps-container">
                <div class="w-40 text-center group step-item">
                    <div class="w-16 h-16 mx-auto bg-brand-blue rounded-full flex items-center justify-center text-xl font-bold mb-4 shadow-[0_0_20px_rgba(30,64,175,0.5)] group-hover:scale-110 transition">1</div>
                    <h4 class="font-bold">Sign In</h4>
                    <p class="text-xs text-gray-400 mt-2">Unlock possibilities with a single click.</p>
                </div>
                <div class="w-40 text-center group step-item">
                    <div class="w-16 h-16 mx-auto glass-panel border border-gray-600 rounded-full flex items-center justify-center text-xl font-bold mb-4 group-hover:bg-white/10 transition">2</div>
                    <h4 class="font-bold">Open Account</h4>
                    <p class="text-xs text-gray-400 mt-2">Complete the digital form.</p>
                </div>
                <div class="w-40 text-center group step-item">
                    <div class="w-16 h-16 mx-auto glass-panel border border-gray-600 rounded-full flex items-center justify-center text-xl font-bold mb-4 group-hover:bg-white/10 transition">3</div>
                    <h4 class="font-bold">Verification</h4>
                    <p class="text-xs text-gray-400 mt-2">Verify Email & Mobile via OTP.</p>
                </div>
                <div class="w-40 text-center group step-item">
                    <div class="w-16 h-16 mx-auto glass-panel border border-gray-600 rounded-full flex items-center justify-center text-xl font-bold mb-4 group-hover:bg-white/10 transition">4</div>
                    <h4 class="font-bold">Deposit Funds</h4>
                    <p class="text-xs text-gray-400 mt-2">Add funds instantly to activate.</p>
                </div>
                <div class="w-40 text-center group step-item">
                    <div class="w-16 h-16 mx-auto glass-panel border border-gray-600 rounded-full flex items-center justify-center text-xl font-bold mb-4 group-hover:bg-white/10 transition">5</div>
                    <h4 class="font-bold">Get Service</h4>
                    <p class="text-xs text-gray-400 mt-2">Enjoy premium banking.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-brand-dark border-t border-gray-800">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold mb-10 text-center">What People Say</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 testimonials-grid">
                <div class="glass-panel p-6 rounded-xl relative testimonial-card">
                    <i class="ri-double-quotes-l text-4xl text-brand-blue opacity-50 absolute top-4 left-4"></i>
                    <p class="text-gray-300 text-sm mt-8 mb-4 relative z-10">"Experience lightning-fast transfers with our seamless and secure transaction platform."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full"></div>
                        <span class="font-bold text-sm">Rakesh</span>
                    </div>
                </div>
                <div class="glass-panel p-6 rounded-xl relative testimonial-card">
                    <i class="ri-double-quotes-l text-4xl text-brand-blue opacity-50 absolute top-4 left-4"></i>
                    <p class="text-gray-300 text-sm mt-8 mb-4 relative z-10">"Effortlessly deposit funds into your account, ensuring a swift and hassle-free process."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-teal-500 rounded-full"></div>
                        <span class="font-bold text-sm">Bhavesh</span>
                    </div>
                </div>
                <div class="glass-panel p-6 rounded-xl relative testimonial-card">
                    <i class="ri-double-quotes-l text-4xl text-brand-blue opacity-50 absolute top-4 left-4"></i>
                    <p class="text-gray-300 text-sm mt-8 mb-4 relative z-10">"Enjoy quick and convenient fund withdrawals, providing you with instant access to your finances."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-500 rounded-full"></div>
                        <span class="font-bold text-sm">Vignesh</span>
                    </div>
                </div>
                <div class="glass-panel p-6 rounded-xl relative testimonial-card">
                    <i class="ri-double-quotes-l text-4xl text-brand-blue opacity-50 absolute top-4 left-4"></i>
                    <p class="text-gray-300 text-sm mt-8 mb-4 relative z-10">"The best digital banking experience I've had. Secure and very easy to use on mobile."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full"></div>
                        <span class="font-bold text-sm">Laxayraj</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="contact" class="bg-[#05080f] pt-16 pb-8 border-t border-gray-800 text-sm">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="text-xl font-bold text-white mb-4"><a href=".\admin\adminlogin.php">Prime Bank </a></div> 
                    <p class="text-gray-500 mb-4">Our Mission: To build domain knowledge based human resource by imparting contemporary technical skills and social.</p>
                    <div class="text-gray-400 mb-2"><i class="ri-map-pin-line"></i> Manipal University, Jaipur</div>
                    <div class="text-gray-400 mb-2"><i class="ri-mail-line"></i> primebank@gmail.com</div>
                    <div class="text-gray-400"><i class="ri-phone-fill"></i> +01 234 567 88</div>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Banking Services</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li><a href="#" class="hover:text-blue-400">Personal Banking</a></li>
                        <li><a href="#" class="hover:text-blue-400">Corporate Banking</a></li>
                        <li><a href="#" class="hover:text-blue-400">NRI Services</a></li>
                        <li><a href="#" class="hover:text-blue-400">Credit Cards</a></li>
                        <li><a href="#" class="hover:text-blue-400">Interest Rates</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Legal & Regulatory</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li><a href="#" class="hover:text-red-400">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-red-400">Terms & Conditions</a></li>
                        <li><a href="#" class="hover:text-red-400">RBI Disclosures</a></li>
                        <li><a href="#" class="hover:text-red-400">KYC/AML Compliance</a></li>
                        <li><a href="#" class="hover:text-red-400">Grievance Redressal</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Customer Support</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li><a href="#" class="hover:text-blue-400">Report Fraud</a></li>
                        <li><a href="#" class="hover:text-blue-400">Branch Locator</a></li>
                        <li><a href="#" class="hover:text-blue-400">Download Forms</a></li>
                        <li><a href="#" class="hover:text-blue-400">Contact Us</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-900 pt-8 text-xs text-gray-600 text-center">
                <p class="mb-2">Prime Bank is registered under the Banking Regulation Act. All deposits are insured up to ₹5 Lakhs by DICGC.</p>
                <p>&copy; 2024 Prime Bank rvb. Rights Reserved. Designed by Rakesh Mali.</p>
            </div>
        </div>
    </footer>

    <div class="fixed bottom-6 right-6 z-50">
        <button class="w-14 h-14 bg-brand-blue rounded-full shadow-[0_0_20px_rgba(30,64,175,0.6)] flex items-center justify-center text-white text-2xl hover:scale-110 transition animate-bounce">
            <i class="ri-chat-smile-2-fill"></i>
        </button>
    </div>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Header Fade In
        gsap.from(".hero-content > *", { opacity: 0, y: 50, duration: 1.2, stagger: 0.2, ease: "power3.out" });
        gsap.from(".hero-card", { opacity: 0, x: 50, duration: 1.2, delay: 0.5, ease: "power3.out" });

        // Stats Bar
        gsap.from(".stats-bar", {
            scrollTrigger: { trigger: ".stats-bar", start: "top 90%" },
            opacity: 0, scale: 0.95, duration: 1
        });

        // About Us
        gsap.from(".about-text", {
            scrollTrigger: { trigger: "#about", start: "top 80%" },
            x: -50, opacity: 0, duration: 1
        });
        gsap.from(".about-visual", {
            scrollTrigger: { trigger: "#about", start: "top 80%" },
            x: 50, opacity: 0, duration: 1
        });

        // Services Stagger
        gsap.from(".service-card", {
            scrollTrigger: { trigger: "#services", start: "top 80%" },
            y: 50, opacity: 0, duration: 0.8, stagger: 0.1
        });

        // Digital Section
        gsap.from(".digital-content", {
            scrollTrigger: { trigger: "#digital", start: "top 70%" },
            x: -50, opacity: 0, duration: 1
        });
        gsap.from(".digital-visual", {
            scrollTrigger: { trigger: "#digital", start: "top 70%" },
            y: 100, opacity: 0, duration: 1.2
        });

        // Loans Table
        gsap.from(".loan-table", {
            scrollTrigger: { trigger: "#loans", start: "top 85%" },
            y: 30, opacity: 0, duration: 1
        });

        // Security
        gsap.from(".security-text", {
            scrollTrigger: { trigger: "#security", start: "top 80%" },
            x: -50, opacity: 0, duration: 1
        });
        gsap.from(".security-card", {
            scrollTrigger: { trigger: "#security", start: "top 80%" },
            scale: 0.8, opacity: 0, duration: 0.8
        });

        // How It Works Steps
        gsap.from(".step-item", {
            scrollTrigger: { trigger: ".steps-container", start: "top 85%" },
            y: 50, opacity: 0, duration: 0.6, stagger: 0.15
        });

        // Testimonials
        gsap.from(".testimonial-card", {
            scrollTrigger: { trigger: ".testimonials-grid", start: "top 85%" },
            opacity: 0, y: 30, duration: 0.8, stagger: 0.1
        });
        
         // Init Tilt Effect for Card
        VanillaTilt.init(document.querySelector(".atm-card"), {
            max: 25,
            speed: 400,
            glare: true,
            "max-glare": 0.5,
        });

        gsap.registerPlugin(ScrollTrigger);
        
        // Animations
        gsap.from(".service-card", {
            scrollTrigger: { trigger: "#services", start: "top 80%" },
            y: 50, opacity: 0, duration: 0.8, stagger: 0.1
        });

        gsap.from(".atm-card", {
            scrollTrigger: { trigger: "#cards", start: "top 75%" },
            x: 100, opacity: 0, duration: 1, ease: "power2.out"
        });
    </script>
</body>
</html>
