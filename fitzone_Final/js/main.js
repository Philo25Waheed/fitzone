/**
 * =====================================================
 * FITZONE GYM - Main JavaScript
 * =====================================================
 * Handles authentication, progress tracking, and UI
 * Connected to PHP backend via Fetch API
 */

const App = {
  // API base URL - automatically detect based on current location
  API_BASE: (function () {
    // Get the path up to the project folder
    const path = window.location.pathname;
    const match = path.match(/^(.*fitzone_gym_test)/i);
    return match ? match[1] : '';
  })(),

  // Current user data
  user: null,


  /**
   * Initialize the application
   */
  init() {
    this.cache();
    this.bind();
    this.checkSession();
    this.updateStreakUI();
  },

  /**
   * Cache DOM elements
   */
  cache() {
    this.body = document.body;
    this.loginForm = document.getElementById('loginForm');
    this.registerForm = document.getElementById('registerForm');
    this.contactForm = document.getElementById('contactForm');
    this.logoutBtn = document.getElementById('logoutBtn');
    this.calForm = document.getElementById('calForm');
    this.streakElem = document.getElementById('streakCount');
    this.exerciseList = document.getElementById('exerciseList');
    this.userEmailElem = document.getElementById('userEmail');
  },

  /**
   * Bind event listeners
   */
  bind() {
    // Login form
    if (this.loginForm) {
      this.loginForm.addEventListener('submit', e => {
        e.preventDefault();
        this.login();
      });
    }

    // Register form
    if (this.registerForm) {
      this.registerForm.addEventListener('submit', e => {
        e.preventDefault();
        this.register();
      });
    }

    // Contact form
    if (this.contactForm) {
      this.contactForm.addEventListener('submit', e => {
        e.preventDefault();
        this.submitContact();
      });
    }

    // Logout button
    if (this.logoutBtn) {
      this.logoutBtn.addEventListener('click', () => this.logout());
    }

    // Calorie calculator form
    if (this.calForm) {
      this.calForm.addEventListener('submit', e => {
        e.preventDefault();
        this.calcCalories();
      });
    }

    // Render exercises if on services page
    this.renderExercises();
  },

  /**
   * Check if user has active session
   */
  async checkSession() {
    try {
      const response = await fetch(this.API_BASE + '/auth/check.php', {
        method: 'GET',
        credentials: 'include'
      });

      const data = await response.json();

      if (data.success && data.data.authenticated) {
        this.user = data.data.user;
        this.updateAuthUI();

        // Update streak from server
        if (this.streakElem && data.data.streak !== undefined) {
          this.streakElem.textContent = data.data.streak;
        }
      } else {
        this.user = null;
        this.updateAuthUI();
      }
    } catch (error) {
      console.error('Session check error:', error);
      this.user = null;
      this.updateAuthUI();
    }
  },

  /**
   * Login user
   */
  async login() {
    const email = document.getElementById('email')?.value?.trim();
    const password = document.getElementById('password')?.value;

    if (!email || !password) {
      this.showMessage('يرجى إدخال البريد وكلمة المرور', 'error');
      return;
    }

    try {
      const response = await fetch(this.API_BASE + '/auth/login.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        credentials: 'include',
        body: JSON.stringify({ email, password })
      });

      const data = await response.json();

      if (data.success) {
        this.user = data.data.user;
        this.showMessage('تم تسجيل الدخول بنجاح!', 'success');
        this.updateAuthUI();

        // Redirect to home page after short delay
        setTimeout(() => {
          window.location.href = 'index.html';
        }, 1000);
      } else {
        this.showMessage(data.message || 'فشل تسجيل الدخول', 'error');
      }
    } catch (error) {
      console.error('Login error:', error);
      this.showMessage('خطأ في الاتصال بالخادم', 'error');
    }
  },

  /**
   * Register new user
   */
  async register() {
    const name = document.getElementById('name')?.value?.trim();
    const email = document.getElementById('email')?.value?.trim();
    const password = document.getElementById('password')?.value;

    if (!name || !email || !password) {
      this.showMessage('يرجى ملء جميع الحقول', 'error');
      return;
    }

    if (password.length < 6) {
      this.showMessage('كلمة المرور يجب أن تكون 6 أحرف على الأقل', 'error');
      return;
    }

    try {
      const response = await fetch(this.API_BASE + '/auth/register.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        credentials: 'include',
        body: JSON.stringify({ name, email, password })
      });

      const data = await response.json();

      if (data.success) {
        this.showMessage('تم التسجيل بنجاح! يرجى تسجيل الدخول', 'success');

        // Redirect to login page after short delay
        setTimeout(() => {
          window.location.href = 'login.html';
        }, 1500);
      } else {
        this.showMessage(data.message || 'فشل التسجيل', 'error');
      }
    } catch (error) {
      console.error('Register error:', error);
      this.showMessage('خطأ في الاتصال بالخادم', 'error');
    }
  },

  /**
   * Logout user
   */
  async logout() {
    try {
      const response = await fetch(this.API_BASE + '/auth/logout.php', {
        method: 'POST',
        credentials: 'include'
      });

      const data = await response.json();

      this.user = null;
      this.updateAuthUI();
      this.showMessage('تم تسجيل الخروج', 'success');

      // Redirect to home
      setTimeout(() => {
        window.location.href = 'index.html';
      }, 500);

    } catch (error) {
      console.error('Logout error:', error);
      this.user = null;
      this.updateAuthUI();
    }
  },

  /**
   * Update UI based on authentication state
   */
  updateAuthUI() {
    const authOnlyElements = document.querySelectorAll('.auth-only');
    const guestOnlyElements = document.querySelectorAll('.guest-only');

    if (this.user) {
      authOnlyElements.forEach(el => el.style.display = 'inline-block');
      guestOnlyElements.forEach(el => el.style.display = 'none');

      if (this.userEmailElem) {
        this.userEmailElem.textContent = this.user.email || this.user.name;
      }
    } else {
      authOnlyElements.forEach(el => el.style.display = 'none');
      guestOnlyElements.forEach(el => el.style.display = 'inline-block');
    }
  },

  /**
   * Submit contact form
   */
  async submitContact() {
    const name = document.getElementById('contactName')?.value?.trim();
    const email = document.getElementById('contactEmail')?.value?.trim();
    const message = document.getElementById('contactMessage')?.value?.trim();

    if (!name || !email || !message) {
      this.showMessage('يرجى ملء جميع الحقول', 'error');
      return;
    }

    try {
      const response = await fetch(this.API_BASE + '/api/contact.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name, email, message })
      });

      const data = await response.json();

      if (data.success) {
        this.showMessage('تم إرسال رسالتك بنجاح!', 'success');
        this.contactForm.reset();
      } else {
        this.showMessage(data.message || 'فشل إرسال الرسالة', 'error');
      }
    } catch (error) {
      console.error('Contact form error:', error);
      this.showMessage('خطأ في الاتصال بالخادم', 'error');
    }
  },

  /**
   * Mark workout as done (save to database)
   */
  async markWorkoutDone() {
    if (!this.user) {
      this.showMessage('يرجى تسجيل الدخول أولاً', 'error');
      return;
    }

    try {
      const response = await fetch(this.API_BASE + '/api/progress.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        credentials: 'include',
        body: JSON.stringify({
          date: new Date().toISOString().split('T')[0]
        })
      });

      const data = await response.json();

      if (data.success) {
        const streak = data.data.streak || 0;
        if (this.streakElem) {
          this.streakElem.textContent = streak;
        }
        this.showMessage('تم تسجيل التمرين! أيام الحماس: ' + streak, 'success');
      } else {
        this.showMessage(data.message || 'فشل تسجيل التمرين', 'error');
      }
    } catch (error) {
      console.error('Progress error:', error);
      this.showMessage('خطأ في الاتصال بالخادم', 'error');
    }
  },

  /**
   * Update streak display
   */
  async updateStreakUI() {
    // Streak is now updated via checkSession
    // This is a fallback for localStorage (for guests)
    if (!this.user && this.streakElem) {
      const streak = parseInt(localStorage.getItem('streak') || '0');
      this.streakElem.textContent = streak;
    }
  },

  /**
   * Render exercises list
   */
  renderExercises() {
    if (!this.exerciseList) return;

    const exercises = [
      { title: 'Push Ups', video: 'https://www.youtube.com/embed/_l3ySVKYVJ8' },
      { title: 'Squats', video: 'https://www.youtube.com/embed/aclHkVaku9U' },
      { title: 'Plank', video: 'https://www.youtube.com/embed/pSHjTRCQxIw' },
      { title: 'Deadlift', video: 'https://www.youtube.com/embed/op9kVnSso6Q' },
      { title: 'Shoulder Press', video: 'https://www.youtube.com/embed/qEwKCR5JCog' }
    ];

    exercises.forEach(ex => {
      const div = document.createElement('div');
      div.className = 'card';
      div.innerHTML = `
                <h3>${ex.title}</h3>
                <p>شرح التمرين من مدرب محترف</p>
                <div style="margin-top:8px">
                    <button class="btn" onclick="App.openVideo('${ex.video}')">شاهد الفيديو</button>
                </div>
            `;
      this.exerciseList.appendChild(div);
    });
  },

  /**
   * Open video modal
   */
  openVideo(src) {
    const modal = document.getElementById('videoModal');
    if (!modal) return;

    modal.innerHTML = `
            <div style="position:relative;padding-top:56.25%">
                <iframe src="${src}" style="position:absolute;left:0;top:0;width:100%;height:100%" frameborder="0" allowfullscreen></iframe>
            </div>
            <div style="text-align:right;margin-top:8px">
                <button class="btn" onclick="App.closeVideo()">إغلاق</button>
            </div>
        `;
    modal.style.display = 'block';
  },

  /**
   * Close video modal
   */
  closeVideo() {
    const modal = document.getElementById('videoModal');
    if (modal) {
      modal.style.display = 'none';
      modal.innerHTML = '';
    }
  },

  /**
   * Show message to user
   */
  showMessage(message, type = 'info') {
    // Remove existing messages
    const existing = document.querySelector('.app-message');
    if (existing) existing.remove();

    // Create message element
    const msgDiv = document.createElement('div');
    msgDiv.className = 'app-message';
    msgDiv.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        `;

    // Set color based on type
    if (type === 'success') {
      msgDiv.style.background = 'linear-gradient(135deg, #10b981, #059669)';
    } else if (type === 'error') {
      msgDiv.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
    } else {
      msgDiv.style.background = 'linear-gradient(135deg, #6366f1, #4f46e5)';
    }

    msgDiv.textContent = message;
    document.body.appendChild(msgDiv);

    // Auto remove after 3 seconds
    setTimeout(() => {
      msgDiv.style.opacity = '0';
      msgDiv.style.transition = 'opacity 0.3s';
      setTimeout(() => msgDiv.remove(), 300);
    }, 3000);
  },

  /**
   * Calculate calories (basic)
   */
  calcCalories() {
    const weight = parseFloat(document.getElementById('weight')?.value);
    const height = parseFloat(document.getElementById('height')?.value);
    const age = parseInt(document.getElementById('age')?.value);
    const gender = document.getElementById('gender')?.value;

    if (!weight || !height || !age) {
      this.showMessage('الرجاء إدخال جميع القيم', 'error');
      return;
    }

    // Mifflin-St Jeor Equation
    let bmr = gender === 'male'
      ? 10 * weight + 6.25 * height - 5 * age + 5
      : 10 * weight + 6.25 * height - 5 * age - 161;

    const activity = parseFloat(document.getElementById('activity')?.value || 1.55);
    const calories = Math.round(bmr * activity);

    const resultElem = document.getElementById('calResult');
    if (resultElem) {
      resultElem.textContent = calories + ' kcal/day';
    }
  },

  /**
   * Advanced calorie & macro calculator
   */
  calcAdvanced() {
    const w = parseFloat(document.getElementById('adv_weight')?.value);
    const h = parseFloat(document.getElementById('adv_height')?.value);
    const age = parseInt(document.getElementById('adv_age')?.value);
    const gender = document.getElementById('adv_gender')?.value;
    const activity = parseFloat(document.getElementById('adv_activity')?.value);
    const goal = document.getElementById('adv_goal')?.value;

    if (!w || !h || !age) {
      this.showMessage('الرجاء إدخال القيم', 'error');
      return;
    }

    let bmr = gender === 'male'
      ? 10 * w + 6.25 * h - 5 * age + 5
      : 10 * w + 6.25 * h - 5 * age - 161;

    let maintenance = Math.round(bmr * activity);
    let calories = maintenance;

    if (goal === 'bulking') calories = Math.round(maintenance * 1.15);
    if (goal === 'cutting') calories = Math.round(maintenance * 0.8);

    const macros = {
      bulking: { protein: 0.30, carbs: 0.50, fat: 0.20 },
      cutting: { protein: 0.40, carbs: 0.30, fat: 0.30 },
      maintenance: { protein: 0.35, carbs: 0.45, fat: 0.20 }
    };

    const m = macros[goal] || macros.maintenance;
    const protein_g = Math.round((calories * m.protein) / 4);
    const carbs_g = Math.round((calories * m.carbs) / 4);
    const fat_g = Math.round((calories * m.fat) / 9);

    const resultsDiv = document.getElementById('advResults');
    if (resultsDiv) {
      resultsDiv.innerHTML = `
                <div class="result-card"><h4>Calories</h4><p>${calories} kcal/day</p></div>
                <div class="result-card"><h4>Protein</h4><p>${protein_g} g</p></div>
                <div class="result-card"><h4>Carbs</h4><p>${carbs_g} g</p></div>
                <div class="result-card"><h4>Fat</h4><p>${fat_g} g</p></div>
            `;
    }
  },

  /**
   * Open split modal
   */
  openSplit(key) {
    const content = document.getElementById('splitContent');
    const map = {
      bro: {
        title: 'Bro Split',
        body: `<h3>Bro Split</h3><p>السبت: صدر<br>الأحد: ظهر<br>الإثنين: كتف<br>الثلاثاء: رجل<br>الأربعاء: ذراع<br>الخميس/الجمعة: راحة</p><p>تمارين عزل كثيرة · عدد مجموعات كبير · مناسب للتضخيم</p>`
      },
      full: {
        title: 'Full Body',
        body: `<h3>Full Body</h3><p>الجسم كله في نفس اليوم · 3-4 مرات أسبوعيًا</p><p>تمارين مركبة أساسية · التركيز على التكنيك والقوة</p>`
      },
      pushpull: {
        title: 'Push / Pull',
        body: `<h3>Push / Pull</h3><p>Push: صدر+كتف+تراي · Pull: ظهر+باي · 4-5 أيام/أسبوع</p><p>تمارين مركبة بنسبة 70%</p>`
      },
      bodypart: {
        title: 'Body Part Split',
        body: `<h3>Body Part Split</h3><p>اليوم 1: عضلات كبيرة (صدر+ظهر+رجلين)<br>اليوم 2: عضلات صغيرة (كتف+باي+تراي)</p><p>Supersets · Drop sets · مناسب ل3-4 أيام/أسبوع</p>`
      },
      power: {
        title: 'Powerbuilding',
        body: `<h3>Powerbuilding</h3><p>مزيج قوة + ضخامة · 4-5 أيام/أسبوع</p><p>تمارين أساسية ثقيلة + تمارين عزل خفيفة · تكرارات منخفضة في أيام القوة (3–5) وعالية في أيام الضخامة (8–12)</p>`
      }
    };

    const d = map[key];
    if (!d || !content) return;

    content.innerHTML = `
            <div style="display:flex;gap:12px">
                <div style="flex:1">
                    <h2 style="color:var(--neon)">${d.title}</h2>
                    ${d.body}
                </div>
                <div style="width:220px">
                    <button class="btn" onclick="App.closeSplit()">إغلاق</button>
                </div>
            </div>
        `;

    const modal = document.getElementById('splitModal');
    if (modal) modal.style.display = 'flex';
  },

  /**
   * Close split modal
   */
  closeSplit() {
    const modal = document.getElementById('splitModal');
    const content = document.getElementById('splitContent');
    if (modal) modal.style.display = 'none';
    if (content) content.innerHTML = '';
  }
};

// Initialize app when DOM is ready
window.addEventListener('DOMContentLoaded', () => App.init());

// Modal backdrop close handling
document.addEventListener('click', function (e) {
  const backdrop = document.getElementById('splitModal');
  if (backdrop && backdrop.style.display === 'flex' && e.target === backdrop) {
    App.closeSplit();
  }
});

// Escape key closes modals
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') {
    App.closeSplit();
    App.closeVideo();
  }
});

// Reveal animations
document.addEventListener('DOMContentLoaded', function () {
  setTimeout(function () {
    document.querySelectorAll('.fade-slide').forEach(function (el) {
      el.classList.add('show');
    });
  }, 120);
});
