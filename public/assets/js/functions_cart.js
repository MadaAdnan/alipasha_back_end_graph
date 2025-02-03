
function addCart(product,productImg,userImg){
    console.log(user.typeof)
    var productData=JSON.parse(product);
    let items = JSON.parse(localStorage.getItem("carts")) || [];
    let itemIndex = items.findIndex(item => item.productId === newProductId);

    if (itemIndex !== -1) {
        // إذا كان العنصر موجودًا، قم بزيادة الكمية
        items[itemIndex].qty += 1;
    } else {
        // إذا لم يكن موجودًا، أضفه إلى المصفوفة
        items.push({ productId: newProductId, qty: 1 });
    }

// تحديث localStorage بالمصفوفة الجديدة
    localStorage.setItem("carts", JSON.stringify(items));




    console.log(productData)
}
