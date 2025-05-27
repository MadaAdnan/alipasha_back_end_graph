function Like(url,ID){
    fetch(`${url}/${userId}/${ProductId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('حدث خطأ في الاستجابة');
            }
           // console.log(response.text())
            return response.text(); // أو response.json() إن كانت الاستجابة JSON
        })
        .then(data => {
      document.getElementById(ID).innerText=data;
            console.log('الاستجابة:', data);
        })
        .catch(error => {
            console.error('خطأ:', error);
        });
}
