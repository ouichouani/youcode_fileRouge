interface Product {
  id: number;
  name: string;
  price: number;
  category: string;
  stock: number;
}

const products: Product[] = [
  { id: 1, name: "Keyboard", price: 45, category: "Tech", stock: 10 },
  { id: 2, name: "Mouse", price: 25, category: "Tech", stock: 5 },
  { id: 3, name: "Notebook", price: 8, category: "Office", stock: 30 },
];

function groupProductsByCategory(products: Product[]): Record<string, Product[]> {
  return products.reduce((acc, product) => {
    if (!acc[product.category]) acc[product.category] = [];
    acc[product.category].push(product);
    return acc;
  }, {});
}

function calculateInventoryValue(products: Product[]): number {
  const totalStock = products.reduce((sum, product) => sum + product.stock, 0);
  return totalStock * products.reduce((sum, product) => sum + product.price, 0);
}

function debounce(callback: () => void, delay: number): () => void {
  let timeoutId: NodeJS.Timeout;
  return function() {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => callback(), delay);
    };
}

function deepClone(obj: any): any {
  const newObj = Object.assign({}, obj);
  for (const key in newObj) {
    if (typeof newObj[key] === 'object') {
      newObj[key] = deepClone(newObj[key]);
    }
  }
  return newObj;

}

function fibonacci(){