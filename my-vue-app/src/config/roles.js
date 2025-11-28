// src/config/roles.js
// 
// ROLE-BASED ACCESS CONTROL CONFIGURATION
// 
// This file defines which roles can access which routes/features.
// Edit this file to easily manage role permissions across your application.
//

/**
 * Route Access Configuration
 * 
 * Define which roles can access each route.
 * - Use '*' to allow all authenticated users
 * - Use specific role slugs to restrict access
 * - Admin role always has full access by default
 */
export const routeAccess = {
    dashboard: ['*'],              // All authenticated users
    projects: ['*'],               // All authenticated users
    clients: ['admin'],            // Admin only
    reports: ['admin'],            // Admin only
    settings: ['admin'],           // Admin only
    
    // Add more routes here as needed:
    // team: ['admin', 'manager'],
    // analytics: ['admin', 'manager', 'analyst'],
  };
  
  /**
   * Menu Items Configuration
   * 
   * Define the sidebar menu structure with role-based visibility.
   * Each menu item requires:
   * - title: Display name
   * - path: Route path
   * - icon: FontAwesome icon class
   * - roles: Array of role slugs that can see this menu item (use '*' for all)
   */
  export const menuItems = [
    {
      title: 'Dashboard',
      path: '/dashboard',
      icon: 'fas fa-tachometer-alt',
      roles: ['*'],  // Visible to all authenticated users
    },
    {
      title: 'Projects',
      path: '/projects',
      icon: 'fas fa-project-diagram',
      roles: ['*'],  // Visible to all authenticated users
    },
    {
      title: 'Clients',
      path: '/clients',
      icon: 'fas fa-users',
      roles: ['admin'],  // Admin only
    },
    {
      title: 'Reports',
      path: '/reports',
      icon: 'fas fa-chart-bar',
      roles: ['admin'],  // Admin only
    },
    {
      title: 'Settings',
      path: '/settings',
      icon: 'fas fa-cog',
      roles: ['admin'],  // Admin only
    },
    
    // Example: Add more menu items as needed
    // {
    //   title: 'Team',
    //   path: '/team',
    //   icon: 'fas fa-user-friends',
    //   roles: ['admin', 'manager'],
    // },
    // {
    //   title: 'Analytics',
    //   path: '/analytics',
    //   icon: 'fas fa-chart-line',
    //   roles: ['admin', 'manager', 'analyst'],
    // },
  ];
  
  /**
   * Special Roles Configuration
   * 
   * Define special role behaviors
   */
  export const specialRoles = {
    // Role that has access to everything
    superRole: 'admin',
    
    // Default role for new users (if needed)
    defaultRole: 'user',
  };
  
  /**
   * Helper function to check if a user has access to a route
   * 
   * @param {string} routeName - The route name to check
   * @param {string} userRole - The user's role slug
   * @returns {boolean} - Whether the user has access
   */
  export function hasRouteAccess(routeName, userRole) {
    // Admin always has access
    if (userRole === specialRoles.superRole) {
      return true;
    }
    
    // Check if route exists in configuration
    const allowedRoles = routeAccess[routeName];
    if (!allowedRoles) {
      return false;
    }
    
    // Check if route allows all authenticated users
    if (allowedRoles.includes('*')) {
      return true;
    }
    
    // Check if user's role is in the allowed roles
    return allowedRoles.includes(userRole);
  }
  
  /**
   * Helper function to filter menu items based on user role
   * 
   * @param {string} userRole - The user's role slug
   * @returns {Array} - Filtered menu items
   */
  export function getMenuItemsForRole(userRole) {
    // Admin sees all menu items
    if (userRole === specialRoles.superRole) {
      return menuItems;
    }
    
    // Filter menu items based on role
    return menuItems.filter(item => {
      // Show if item allows all authenticated users
      if (item.roles.includes('*')) {
        return true;
      }
      
      // Show if user's role is in the allowed roles
      return item.roles.includes(userRole);
    });
  }
  
  /**
   * Helper function to get allowed route names for a role
   * 
   * @param {string} userRole - The user's role slug
   * @returns {Array} - Array of allowed route names
   */
  export function getAllowedRoutes(userRole) {
    if (userRole === specialRoles.superRole) {
      return Object.keys(routeAccess);
    }
    
    return Object.keys(routeAccess).filter(routeName => 
      hasRouteAccess(routeName, userRole)
    );
  }
  
  export default {
    routeAccess,
    menuItems,
    specialRoles,
    hasRouteAccess,
    getMenuItemsForRole,
    getAllowedRoutes,
  };