

const article = document.querySelector("article");
const text =
  "隨著長大，越加覺得生活的一切轉變的好快，在社會裡跌跌撞撞後，看清了一些事，只好變成一個表面的人，但這樣壓抑其實挺難受。我想要有一個角落，可以慢下來，可以靜靜的一起揮灑那被封住的喜怒哀樂、對人事物的感觸。";
const interval = 75;
let index = 0;
let animation;
article.style.animation = "show 8s linear forwards";
animation = setInterval(() => {
  article.innerText += text[index];
  index == text.length - 1 ? clearInterval(animation) : index++;
}, interval);

