// Common utility functions for all pages

// Create scroll animation function
function initScrollAnimations() {
  const faders = document.querySelectorAll('.fade-in');

  const appearOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -100px 0px"
  };

  const appearOnScroll = new IntersectionObserver(function(entries, appearOnScroll) {
    entries.forEach(entry => {
      if (!entry.isIntersecting) {
        return;
      } else {
        entry.target.classList.add('show');
        appearOnScroll.unobserve(entry.target);
      }
    });
  }, appearOptions);

  faders.forEach(fader => {
    appearOnScroll.observe(fader);
  });
}

// Função para implementar o scroll suave
function initSmoothScroll() {
  // Seleciona todos os links com âncoras (que começam com #)
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      
      const targetId = this.getAttribute('href');
      
      // Apenas rola se o elemento existir
      if (document.querySelector(targetId)) {
        document.querySelector(targetId).scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
}

// Criando efeito de "ripple" para botões
function initRippleEffect() {
  const buttons = document.querySelectorAll('.btn');
  
  buttons.forEach(button => {
    button.addEventListener('click', function(e) {
      const rect = button.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      const ripple = document.createElement('span');
      ripple.classList.add('ripple');
      ripple.style.left = `${x}px`;
      ripple.style.top = `${y}px`;
      
      button.appendChild(ripple);
      
      setTimeout(() => {
        ripple.remove();
      }, 600);
    });
  });
}

// Adiciona partículas de fundo com canvas - versão simplificada
function initParticleBackground() {
  // Verifica se o elemento hero existe
  const hero = document.querySelector('.hero');
  if (!hero) return;
  
  // Cria o canvas
  const canvas = document.createElement('canvas');
  canvas.className = 'particles-canvas';
  hero.appendChild(canvas);
  
  const ctx = canvas.getContext('2d');
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
  
  // Configurações
  const particleCount = 40; // Reduzido para melhor performance
  const particles = [];
  
  // Define as cores que correspondem às variáveis CSS
  const colors = [
    '#AF04E8', // primary
    '#FB5304', // secondary
  ];
  
  // Classe partícula
  class Particle {
    constructor() {
      this.x = Math.random() * canvas.width;
      this.y = Math.random() * canvas.height;
      this.size = Math.random() * 2 + 0.5; // Partículas menores
      this.speedX = Math.random() * 0.8 - 0.4; // Movimento mais suave
      this.speedY = Math.random() * 0.8 - 0.4;
      this.color = colors[Math.floor(Math.random() * colors.length)];
      this.opacity = Math.random() * 0.4 + 0.1; // Mais sutil
    }
    
    update() {
      this.x += this.speedX;
      this.y += this.speedY;
      
      // Reposiciona a partícula se ela sair da tela
      if (this.x < 0 || this.x > canvas.width) {
        this.x = Math.random() * canvas.width;
      }
      if (this.y < 0 || this.y > canvas.height) {
        this.y = Math.random() * canvas.height;
      }
    }
    
    draw() {
      ctx.fillStyle = this.color;
      ctx.globalAlpha = this.opacity;
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
      ctx.fill();
    }
  }
  
  // Inicializa partículas
  function init() {
    for (let i = 0; i < particleCount; i++) {
      particles.push(new Particle());
    }
  }
  
  // Anima partículas
  function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    for (let i = 0; i < particles.length; i++) {
      particles[i].update();
      particles[i].draw();
    }
    
    requestAnimationFrame(animate);
  }
  
  init();
  animate();
  
  // Ajustar o canvas quando a janela for redimensionada
  window.addEventListener('resize', function() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  });
}

// Inicializa as funções quando o documento estiver pronto
document.addEventListener('DOMContentLoaded', function() {
  initScrollAnimations();
  initSmoothScroll();
  initRippleEffect();
  initParticleBackground();
}); 