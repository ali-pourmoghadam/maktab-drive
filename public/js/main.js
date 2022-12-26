let btnNew = document.querySelector('.mian-sideBar-btn')
let actionWrapper = document.querySelector('.main-action-warpers')
let body = document.querySelector('body')
let inputFileUpload = document.querySelector('#inputFile')
let uploadTxt = document.querySelector('.upload-modal-txt')
let uploadModalWrapper = document.querySelector(".upload-modal-wrapper")
let mainActionWarpers = document.querySelector(".main-action-warpers")
let btnDismissUpload = document.querySelector(".btn-dismiss-upload")
let closeErrBtn = document.querySelector(".close-err")
let error = document.querySelector(".error")
let rightClickContex = document.querySelector('.right-click-contex')
let fileRename = document.querySelector('.fileRename')
let deleteFile = document.querySelector('.deleteFile')


actionWrapperActive =false


btnNew.onclick = ()=>{

    if(!actionWrapperActive){
        actionWrapper.classList.remove('pagedeActive')
        actionWrapper.classList.add('pageActive')
    }else{
        actionWrapper.classList.remove('pageActive')
        actionWrapper.classList.add('pagedeActive')
    }
    actionWrapperActive = !actionWrapperActive
   
}


body.onclick = (event)=>{

        if(actionWrapperActive && event.target.classList[0] != "mian-sideBar-btn"){  
            actionWrapper.classList.remove('pageActive')
            actionWrapper.classList.add('pagedeActive')
            actionWrapperActive = !actionWrapperActive
        }

        toggle(false ,rightClickContex)
  }
    




  body.oncontextmenu = (e)=>{
    e.preventDefault()

    parentClassList = e.target.parentNode.classList
    targetClassList = e.target.classList
    isChilde = false

    parentClassList.forEach(element => {
      if(element == 'files'){
        isChilde = true
      }
    });

    if( targetClassList == 'files' || isChilde){
     
      rightClickContex.style.top = e.clientY+"px"
      rightClickContex.style.left = e.clientX+"px"
      toggle(true ,rightClickContex)
    }

    if(e.target.localName == "img"){
 
      fileRename.value = e.target.alt
      deleteFile.value = e.target.alt

    }else{
      fileRename.value = e.target.textContent.trim()
      deleteFile.value = e.target.textContent.trim()
    }

  }

  inputFileUpload.onchange = (e)=>{

    let fileName = inputFileUpload.value
    fileName =  fileName.split("\\")
    fileName = fileName[fileName.length-1]
    uploadTxt.innerText = "do you want to upload "+fileName+" ?"
  

    toggle(true , mainActionWarpers)
    toggle(true , uploadModalWrapper)

  }


  btnDismissUpload.onclick = ()=>{

    toggle(false , mainActionWarpers)
    toggle(false , uploadModalWrapper)
  }




  if(closeErrBtn != null){
      closeErrBtn.onclick = ()=>{
        toggle(false , error)
      }
  }




  function toggle(action =true , target){
    if(action){
        target.classList.remove("pagedeActive")
        target.classList.add("pageActive")
    }else{
        target.classList.remove("pageActive")
        target.classList.add("pagedeActive")
    }
  }


