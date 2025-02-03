
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
