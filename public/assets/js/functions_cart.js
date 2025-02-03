
function addCart(product,productImg,userImg){

    var productData=JSON.parse(product);

    let items = JSON.parse(localStorage.getItem("carts")) || [];
    let itemIndex = items.findIndex(item => item.productId === productData.id);

    if (itemIndex !== -1) {
        // إذا كان العنصر موجودًا، قم بزيادة الكمية
        items[itemIndex].qty += 1;
    } else {
        // إذا لم يكن موجودًا، أضفه إلى المصفوفة
        items.push({ productId: productData.id, qty: 1 });
    }

// تحديث localStorage بالمصفوفة الجديدة
    localStorage.setItem("carts", JSON.stringify(items));




    console.log(productData)
}
function deleteFromCart(productId){
    let items = JSON.parse(localStorage.getItem("carts")) || [];
    let itemIndex = items.findIndex(item => item.productId === productId);
    if (itemIndex !== -1) {
        items.splice(itemIndex, 1); // حذف العنصر من المصفوفة
        localStorage.setItem("carts", JSON.stringify(items)); // تحديث localStorage
    }

}

function getCountItemsInCart(){
    let items = JSON.parse(localStorage.getItem("carts")) || [];
    return items.length
}
