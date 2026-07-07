<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);
session_start();

$bg_images = [
  "https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070",
  "https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=2070",
  "https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?q=80&w=2070"
];
$selected_bg = $bg_images[array_rand($bg_images)];

$show_otp_form = false;
$display_email = "";
$expire = 0;

if (isset($_SESSION['register_data'])) {
  $show_otp_form = true;
  $display_email = is_array($_SESSION['register_data']) ? ($_SESSION['register_data']['email'] ?? '') : $_SESSION['register_data'];

  $resent = $conn->prepare("SELECT expire_at FROM user_otp WHERE email = ?");
  $resent->bind_param("s", $display_email);
  $resent->execute();
  $result = $resent->get_result();
  if ($row = $result->fetch_assoc()) {
    $expire = isset($row['expire_at']) ? (int) $row['expire_at'] : 0;
  }
  $resent->close();
} elseif (isset($_SESSION['otp']) || isset($_SESSION['admin_otp'])) {
  $show_otp_form = true;
  $display_email = $_SESSION['otp_email'] ?? $_SESSION['admin_username'] ?? "Your Registered Email";
  $expire = $_SESSION['otp_expire'] ?? $_SESSION['admin_otp_expire'] ?? 0;
}
?>
<!doctype html>
<html lang="en" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TRINITY — AUTHENTICATION</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap"
    rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Montserrat', 'sans-serif'],
            serif: ['Playfair Display', 'serif']
          }
        }
      }
    }
  </script>
  <style>
    body {
      user-select: none;
    }

    .form-container {
      transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }

    input:focus+label,
    input:not(:placeholder-shown)+label {
      transform: translateY(-1.5rem) scale(0.85);
      color: #000;
    }

    .bg-overlay {
      background: linear-gradient(to right, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.1) 100%);
    }
  </style>
</head>

<body class="h-full bg-zinc-50 font-sans text-zinc-900 overflow-hidden">

  <div class="fixed inset-0 z-0 transition-opacity duration-1000">
    <img src="<?= $selected_bg ?>" class="w-full h-full object-cover grayscale-[30%]" alt="Fashion Background">
    <div class="absolute inset-0 bg-overlay"></div>
  </div>

  <main class="relative h-full w-full flex items-center justify-center p-4 sm:p-8 z-10">

    <div
      class="bg-white w-full max-w-[1000px] h-full max-h-[700px] flex overflow-hidden shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)]">

      <div
        class="hidden md:flex md:w-1/2 bg-zinc-900 relative items-center justify-center p-12 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-40">
          <img src="<?= $selected_bg ?>" class="w-full h-full object-cover scale-110" alt="Sidebar view">
        </div>
        <div class="relative z-10 border border-white/30 p-8 text-center backdrop-blur-sm">
          <p class="font-serif italic text-xl mb-4">The Trinity Archive</p>
          <div class="w-12 h-px bg-white mx-auto mb-4"></div>
          <p class="text-[10px] tracking-[0.4em] uppercase opacity-70">Define your identity</p>
        </div>
        <div class="absolute bottom-8 left-8">
          <h1 onclick="window.location.href='../Pages/'"
            class="text-2xl font-serif tracking-widest cursor-pointer hover:opacity-50 transition">TRINITY.</h1>
        </div>
      </div>

      <div class="relative w-full md:w-1/2 flex flex-col relative px-8 py-8 sm:py-12 overflow-y-auto no-scrollbar">

        <div class="mb-3 sm:mb-10 text-center">
          <h2 class="text-xs tracking-[0.5em] uppercase text-zinc-400 mb-2">Member Portal</h2>
          <div class="w-8 h-px bg-zinc-200 mx-auto"></div>
        </div>

        <?php if ($show_otp_form): ?>
          <form action="<?= isset($_SESSION['register_data']) ? '../Database/register.php' : '../Database/login.php' ?>" method="POST" id="otpForm"
            class="space-y-8 animate-in fade-in duration-700">
            <div class="text-center">
              <h3 class="font-serif text-2xl">Security Verification</h3>
              <p class="text-[11px] text-zinc-500 mt-2 uppercase tracking-wide">Sent to:
                <?= htmlspecialchars($display_email) ?></p>
            </div>
            <div class="relative border-b border-zinc-200 focus-within:border-zinc-900 transition-all">
              <input type="hidden" name="email" value="<?= $_SESSION['otp_email'] ?? "" ?>">
              <input type="text" name="<?= isset($_SESSION['register_data']) ? 'registerOtp' : 'otp' ?>" maxlength="6"
                pattern="\d{6}" required placeholder=" "
                class="peer w-full bg-transparent py-3 text-center text-xl tracking-[0.8em] outline-none" />
              <label
                class="absolute left-0 top-3 text-[10px] uppercase tracking-widest text-zinc-400 transition-all pointer-events-none origin-left">Enter
                6-Digit OTP</label>
            </div>
            <button
              class="submit w-full py-4 bg-zinc-900 text-white text-[10px] uppercase tracking-[0.3em] hover:bg-zinc-800 transition shadow-lg">Verify
              Secure Key</button>
            <div class="text-center pt-4">
              <span id="countdown" class="text-[10px] text-zinc-400 tracking-widest"></span>
              <button type="button" id="resent" onclick="window.location.href='../Database/resetOTP.php'"
                class="hidden text-[10px] underline underline-offset-4 tracking-widest uppercase hover:text-black transition">Request
                New Code</button>
            </div>

            <div class="text-center">
              <a href="../Database/outAdmin.php"
                class="text-[10px] text-zinc-400 hover:text-black transition tracking-widest underline underline-offset-4 uppercase">Cancel
                Verification</a>
            </div>

          </form>

        <?php else: ?>

          <form action="../Database/login.php" method="POST" id="loginForm" class="form-container space-y-8 opacity-100">
            <div class="text-center mb-10">
              <h3 class="font-serif text-3xl">Sign In</h3>
            </div>
            <div class="space-y-6">
              <div class="relative border-b border-zinc-200 focus-within:border-zinc-900 transition-all">
                <input type="text" name="email" required placeholder=" "
                  class="peer w-full bg-transparent py-3 outline-none text-sm font-light tracking-wide" />
                <label
                  class="absolute left-0 top-3 text-[10px] uppercase tracking-widest text-zinc-400 transition-all pointer-events-none origin-left">Email</label>
              </div>
              <div class="relative border-b border-zinc-200 focus-within:border-zinc-900 transition-all"
                id="password_layer">
                <input type="password" name="user_password" id="user_password" required placeholder=" "
                  class="peer w-full bg-transparent py-3 outline-none text-sm font-light tracking-wide" />
                <label
                  class="absolute left-0 top-3 text-[10px] uppercase tracking-widest text-zinc-400 transition-all pointer-events-none origin-left">Password</label>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <label class="flex items-center text-[10px] tracking-widest text-zinc-400 cursor-pointer group">
                <input type="checkbox" name="passless" id="passlessCheckbox" value="1" class="hidden peer">
                <div
                  class="w-3 h-3 border border-zinc-300 mr-2 peer-checked:bg-zinc-900 peer-checked:border-zinc-900 transition">
                </div>
                PASSWORDLESS ACCESS
              </label>
              <a href="resetPass.php"
                class="text-[10px] text-zinc-400 hover:text-black tracking-widest transition">Forgot?</a>
            </div>
            <button
              class="submit w-full py-4 bg-zinc-900 text-white text-[10px] uppercase tracking-[0.3em] hover:bg-zinc-800 transition shadow-lg">Authenticate</button>
            <p class="text-center text-[10px] tracking-widest text-zinc-400">
              Not a member? <button type="button"
                class="switch-form-btn text-black font-medium underline underline-offset-4">Register Account</button>
            </p>
          </form>

          <form action="../Database/register.php" method="POST" id="regForm"
            class="form-container space-y-5 hidden opacity-0 translate-y-4">
            <div class="text-center mb-6">
              <h3 class="font-serif text-3xl">Register</h3>
            </div>
            <div class="grid grid-cols-1 gap-4">
              <div class="relative border-b border-zinc-100 py-1">
                <input type="text" name="registerEmail" required placeholder=" "
                  class="peer w-full bg-transparent py-2 text-xs outline-none" />
                <label
                  class="absolute left-0 top-2 text-[9px] uppercase tracking-widest text-zinc-400 transition-all pointer-events-none origin-left">Email
                  <span class="text-[red]">*</span></label>
              </div>
              <div class="relative border-b border-zinc-100 py-1">
                <input type="password" name="user_password" required placeholder=" "
                  class="peer w-full bg-transparent py-2 text-xs outline-none" />
                <label
                  class="absolute left-0 top-2 text-[9px] uppercase tracking-widest text-zinc-400 transition-all pointer-events-none origin-left">Password
                  <span class="text-[red]">*</span></label>
              </div>

              <div class="pt-2">
                <span class="text-[9px] uppercase tracking-[0.2em] text-zinc-400 block mb-2">Gender</span>
                <div class="flex gap-2">
                  <?php foreach (['male', 'female', 'other'] as $sex): ?>
                    <label class="flex-1 cursor-pointer group">
                      <input type="radio" name="user_sex" value="<?= $sex ?>" class="hidden peer" <?= $sex == 'other' ? 'checked' : '' ?>>
                      <div
                        class="text-[9px] py-2 text-center border border-zinc-100 tracking-widest uppercase text-zinc-400 peer-checked:bg-zinc-900 peer-checked:text-white transition">
                        <?= $sex ?>
                      </div>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="relative border-b border-zinc-100 py-1">
                <input type="text" name="user_hotline" pattern="\d{10}" id="hotline" required placeholder=" "
                  class="peer w-full bg-transparent py-2 text-xs outline-none" />
                <label
                  class="absolute left-0 top-2 text-[9px] uppercase tracking-widest text-zinc-400 transition-all pointer-events-none origin-left">Hotline
                  <span class="text-[red]">*</span></label>
              </div>

              <div class="relative address-container border-b border-zinc-100 py-1">
                <input type="text" id="address" name="user_address" oninput="searchAddress(this, 'toList')" required
                  placeholder=" " class="peer w-full bg-transparent py-2 text-xs outline-none" />
                <label
                  class="absolute left-0 top-2 text-[9px] uppercase tracking-widest text-zinc-400 transition-all pointer-events-none origin-left">Geolocation
                  <span class="text-[red]">*</span></label>
                <div id="toList"
                  class="hidden absolute top-full left-0 w-full max-h-32 bg-white shadow-2xl z-50 text-[10px] overflow-y-auto no-scrollbar border-t border-zinc-50">
                </div>
              </div>
            </div>
            <button
              class="submit w-full py-4 bg-zinc-900 text-white text-[10px] uppercase tracking-[0.3em] hover:bg-zinc-800 transition">Create
              Archive</button>
            <p class="text-center text-[10px] tracking-widest text-zinc-400">
              Already registered? <button type="button"
                class="switch-form-btn text-black font-medium underline underline-offset-4">Return to login</button>
            </p>
          </form>
        <?php endif; ?>

        <div
          class="toast text-center absolute bottom-[7%] sm:bottom-[10%] rounded left-[50%] translate-x-[-50%] translate-y-[40px] transition-all duration-300 w-[80%] h-fit p-4 invisible">
        </div>
      </div>
    </div>
  </main>

  <script>
    const switchBtns = document.querySelectorAll(".switch-form-btn");
    const loginForm = document.getElementById("loginForm");
    const regForm = document.getElementById("regForm");

    let isAnimating = false;

    switchBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        if (isAnimating || !loginForm || !regForm) return;

        isAnimating = true;
        const showingLogin = !loginForm.classList.contains('invisible');


        [loginForm, regForm].forEach(f => {
          f.classList.remove('opacity-100', 'translate-y-0');
          f.classList.add('opacity-0', 'translate-y-4');
          setTimeout(() => f.classList.add('invisible', 'hidden'), 300);
        });

        setTimeout(() => {
          const activeForm = showingLogin ? regForm : loginForm;
          activeForm.classList.remove('invisible', 'hidden');

          setTimeout(() => {
            activeForm.classList.remove('opacity-0', 'translate-y-4');
            activeForm.classList.add('opacity-100', 'translate-y-0');

            setTimeout(() => {
              isAnimating = false;
            }, 300);

          }, 50);
        }, 350);
      });
    });

    const hotline = document.getElementById('hotline');
    if (hotline) {
      hotline.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
        if (this.value.length > 0 && this.value[0] !== '0') this.value = '0' + this.value;
        this.parentElement.style.borderBottomColor = (this.value.length === 10) ? "#000" : "#e5e7eb";
      });
    }

    const addressInput = document.getElementById("address");
    const suggestBox = document.getElementById("toList");

    async function searchAddress(input, listId) {
      if (input.value.length < 3) { suggestBox.classList.add('hidden'); return; }
      const res = await fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(input.value)}&limit=5`);
      const data = await res.json();
      suggestBox.innerHTML = "";
      suggestBox.classList.remove('hidden');

      data.features.forEach(place => {
        const name = place.properties.name || place.properties.city || place.properties.country;
        const div = document.createElement("div");
        div.className = "p-3 hover:bg-zinc-50 cursor-pointer border-b border-zinc-50 tracking-widest text-zinc-500 uppercase";
        div.innerText = name;
        div.onclick = () => {
          input.value = name;
          suggestBox.classList.add('hidden');
        };
        suggestBox.appendChild(div);
      });
    }

    const expireTime = <?= json_encode($expire) ?>;
    const countdownEl = document.getElementById("countdown");
    const resentBtn = document.getElementById("resent");

    if (expireTime && countdownEl) {
      function updateCountdown() {
        const now = Math.floor(Date.now() / 1000);
        const rem = expireTime - now;
        if (rem <= 0) {
          countdownEl.style.display = "none";
          if (resentBtn) resentBtn.classList.remove("hidden");
          return;
        }
        const m = Math.floor(rem / 60);
        const s = rem % 60;
        countdownEl.textContent = `RETRY IN ${m}:${s.toString().padStart(2, '0')}`;
      }
      setInterval(updateCountdown, 1000);
      updateCountdown();
    }

    if (new URLSearchParams(window.location.search).get('otp') && regForm) {
      loginForm.classList.add('hidden');
      regForm.classList.remove('hidden', 'opacity-0');
      regForm.classList.add('opacity-100', 'translate-y-0');
    }

    const passless = document.getElementById("passlessCheckbox");
    const passLayer = document.getElementById("password_layer");
    const passInput = document.getElementById("user_password");

    if (passless && passLayer) {
      passless.addEventListener('change', function () {
        if (this.checked) {
          passLayer.classList.add("hidden");
          passInput.removeAttribute("required");
        } else {
          passLayer.classList.remove("hidden");
          passInput.setAttribute("required", "");
        }
      });
    }

    const toast = document.querySelector(".toast");
    let notiTimer = null;

    function toastNoti(data) {
      if (notiTimer) {
        clearTimeout(notiTimer);
      }

      toast.style.background = `${data.color}`;
      toast.classList.remove("invisible", "opactiy-100", "opactiy-0", "translate-y-[60px]");
      toast.classList.add("translate-y-[30px]", "opacity-100");
      toast.textContent = data.message;

      notiTimer = setTimeout(() => {
        toast.classList.remove("translate-y-[30px]", "opacity-100");
        toast.classList.add("invisible", "opactiy-0", "translate-y-[60px]");
      }, 5000);
    }

    const submitBtns = document.querySelectorAll(".submit");

    submitBtns.forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();

        toast.classList.remove("translate-y-[30px]");
        toast.classList.add("invisible", "translate-y-[60px]", "opactiy-0");

        const form = this.closest('form');
        const url = form.getAttribute('action');
        const formData = new FormData(form);

        fetch(url, {
          method: 'POST',
          body: formData
        })
          .then(response => {
            if (!response.ok) throw new Error('Server error, please try again later!');
            return response.json();
          })
          .then(data => {

            if (data.otp == 'required') {
              toastNoti(data);
              setTimeout(() => {
                window.location.reload();
              }, 3000);
            } else if (data.redirect == 'true') {
              setTimeout(() => {
                window.location.href = data.redirectLink;
              }, 100);
            }

            if (data.status == 'OTP_success') {
              toastNoti(data);
              setTimeout(() => {
                window.location.href = '../Pages/home.php';
              }, 1500);
            }

            if (data.status == false) toastNoti(data);

            else toastNoti(data);
          })
          .catch(error => console.error('Error:', error));
      });
    });                        
  </script>
</body>

</html>