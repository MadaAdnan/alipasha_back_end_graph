# Smart Home Query System

## Overview

The Smart Home Query system provides intelligent product recommendations based on user behavior and interactions. It replaces the previous fragmented approach with a unified, efficient solution.

## Features

### 🎯 Intelligent Recommendations
- **User-based suggestions**: Products from categories users interact with most
- **Trending products**: Popular items in user's preferred categories
- **Special products**: Featured/promoted items
- **Fallback diversity**: Random products to ensure variety

### 📊 User Segmentation
- **Authenticated Users**: Personalized recommendations (25% special, 45% interest-based, 20% trending, 10% random)
- **Guest Users**: General recommendations (40% special, 30% latest, 30% random)

### ⚡ Performance Optimizations
- **Caching**: User preferences and trending data cached for better performance
- **Bulk operations**: Efficient view tracking and database queries
- **Service-based architecture**: Reusable components for recommendations and analytics

## GraphQL Usage

### Query
```graphql
query SmartHomeProducts($page: Int, $perPage: Int) {
  smartHomeProducts(page: $page, perPage: $perPage) {
    id
    name
    info
    price
    level
    type
    user {
      name
    }
    category {
      name
    }
  }
}
```

### Variables
```json
{
  "page": 1,
  "perPage": 50
}
```

## Services

### ProductRecommendationService
Handles all recommendation logic:
- `getInterestBasedProducts()`: Products from user's preferred categories
- `getTrendingProducts()`: Popular products with user context
- `getSellerBasedProducts()`: Products from sellers user interacted with
- `getSimilarProducts()`: Related products for product detail pages

### ProductViewTrackingService
Manages view analytics:
- `trackBulkViews()`: Efficient bulk view tracking
- `getProductViewStats()`: Detailed view statistics
- `getTrendingProductIds()`: Most viewed products
- `cleanupOldViews()`: Maintenance for old data

## Database Impact

### Interactions Table
Tracks user behavior:
- `user_id`: User performing the interaction
- `category_id`: Category being interacted with
- `seller_id`: Seller being interacted with
- `visited`: Number of visits/interactions

### Product Views Table
Tracks product visibility:
- `product_id`: Product being viewed
- `view_at`: Date of views
- `count`: Number of views on that date

## Configuration

### Cache Settings
- User interests: 1 hour cache
- Trending products: 30 minutes cache
- Automatic cache invalidation on user interactions

### Distribution Percentages
**Authenticated Users:**
- 25% Special/Featured products
- 45% Interest-based products
- 20% Trending products
- 10% Random products

**Guest Users:**
- 40% Special/Featured products
- 30% Latest products
- 30% Random products

## Migration from Old System

### Deprecated Queries
The following queries are now consolidated into `SmartHomeQuery`:
- `HobbiesProduct` (had broken logic)
- `LatestProduct` (now part of guest recommendations)
- `SpecialProduct` (now part of all recommendations)
- `CombinedProducts` (was incomplete)

### Benefits
1. **Unified Logic**: Single source of truth for home recommendations
2. **Better Performance**: Optimized queries and caching
3. **Improved UX**: More relevant, personalized suggestions
4. **Maintainability**: Clean, service-based architecture
5. **Analytics**: Better tracking and insights

## Testing

Run the test suite:
```bash
php artisan test tests/Feature/SmartHomeQueryTest.php
```

## Monitoring

### Key Metrics to Monitor
- Cache hit rates for user preferences
- Query performance times
- User engagement with recommended products
- Distribution of product types in recommendations

### Performance Considerations
- Monitor database query counts
- Track cache memory usage
- Observe recommendation relevance through user interactions

## Future Enhancements

### Planned Features
1. **Machine Learning**: Advanced recommendation algorithms
2. **A/B Testing**: Test different recommendation strategies
3. **Real-time Updates**: Live recommendation updates
4. **Cross-category Suggestions**: Intelligent category mixing
5. **Seasonal Adjustments**: Time-based recommendation weights
