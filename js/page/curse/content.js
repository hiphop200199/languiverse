import { REQUEST_BASE_URL } from "../admin/config.js"
import { SUCCESS } from "../admin/constant.js"
import { frontend } from "../../request_model.js";

const openStrategyModal = document.getElementById('open-strategy-modal')
const strategyModal = document.getElementById('strategy-modal')
const closeStrategyModal = document.getElementById('cancel')
const form = document.getElementById('strategy-add')
const loadingIcon = document.getElementById('loading-mask')
const alertLB = document.getElementById('alert-mask')
const alertIcon = document.getElementById('result')
const alertBtn = document.getElementById('alert-btn')
const query = new URLSearchParams(location.search)
const id = query.get('id')

openStrategyModal.addEventListener('click',()=>{
    strategyModal.showModal()
    document.documentElement.style.overflowY = 'hidden'
})
closeStrategyModal.addEventListener('click',e=>{
    e.preventDefault()
    strategyModal.close()
    document.documentElement.style.overflowY = 'auto'
})

form.addEventListener('submit',async function(e){
    e.preventDefault()
    try {
        const param = {
          id:id,  
          content: document.querySelector('textarea[name="strategy"]').value,
          task: "create-strategy",
        };
         strategyModal.close()
        loadingIcon.style.display = 'block';
        const response = await frontend('curse',param);
        loadingIcon.style.display = 'none';
        console.log(response);
         if (response.data.errCode === SUCCESS) {
              alertBtn.onclick = function () {
                location.reload();
              };
              alertLB.style.display = "block";
              console.log(response);
            } else {
              alertIcon.src = REQUEST_BASE_URL + '/image/no-data.gif'
              alertBtn.onclick = function () {
                alertLB.style.display = "none";
              };
              alertLB.style.display = "block";
            }
      } catch (error) {
        console.log(error)
      }
})