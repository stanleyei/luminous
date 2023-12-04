// 用於後台選單的路由
const menuLinks = Object.freeze([
  {
    id: 'products',
    name: '商品管理',
    path: 'product.list',
  },
  {
    id: 'clients',
    name: '客戶管理',
    path: 'dashboard',
  },
  {
    id: 'banners',
    name: '橫幅管理',
    path: 'banner.list',
  },
]);

export { menuLinks };
