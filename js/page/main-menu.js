const title = document.getElementById('title')
const links = document.querySelectorAll('.link')
const paragraphs = document.querySelectorAll('p')
const textArray = ['難笑笑話','黑語言防禦術','感人話語','花語收藏'] 
const canvas = document.getElementById('canvas')
const ctx = canvas.getContext('2d')
let timer = 0
let spawnTime = 3000
let lastTime = 0
let particleArray = []
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;
/* paragraphs.forEach((p,i)=>p.innerText = textArray[i])
links.forEach((l,i)=>{
  l.addEventListener('mouseenter',()=> title.innerText = textArray[i])
  l.addEventListener('mouseleave',()=> title.innerText = '你的方向')
})
const linkImages = document.querySelectorAll('img')

for(let i=0;i<linkImages.length;i++){
  linkImages[i].onload = function(){
    linkImages[i].parentElement.style.opacity = '1'
  }
} */

window.addEventListener('resize',function(){
  canvas.width = window.innerWidth;
canvas.height = window.innerHeight;
})

function animate(timeStamp){
  const deltaTime = timeStamp - lastTime;
  lastTime = timeStamp;
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  if(timer >=spawnTime){
    particleArray.push(new Particle());
    timer = 0
  }else{
    timer+=deltaTime
  }
   particleArray.forEach((p, i) => {
      if (p.x > canvas.width || p.x < 0 || p.y > canvas.height || p.y < 0) {
        particleArray.splice(i, 1);
      }
    });
  particleArray.forEach(p=>p.update())
  particleArray.forEach(p=>p.draw(ctx))
  requestAnimationFrame(animate)
}

class Particle {
  constructor() {
   
    this.x = Math.random() * canvas.width;
    this.y = Math.random() * canvas.height;
    this.size = Math.random()*2+1
    this.vx = Math.random()*4-2;
    this.vy = Math.random()*6-3;
    this.ease = 0.085;
  }
  draw(ctx) {
    ctx.fillStyle = 'white';
    ctx.filter = 'drop-shadow(0,0,3,white)';
    ctx.beginPath()
    ctx.arc(this.x,this.y,this.size,0,2*Math.PI);
    ctx.fill();
  }
  update() {
    this.x += this.vx * this.ease;
    this.y += this.vy * this.ease;
  }
}
particleArray.push(new Particle(),new Particle(),new Particle())
animate(0)