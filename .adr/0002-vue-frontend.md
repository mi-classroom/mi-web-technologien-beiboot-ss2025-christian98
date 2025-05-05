# Vue.js/Inertia.js Frontend Stack Selection

* Status: accepted
* Workload: 16 hours
* Decider: [Christian Holländer](https://github.com/christian98)
* Issue: [Kernfunktion im Backend erstelle](https://github.com/mi-classroom/mi-master-wt-beiboot-2025/issues/1), [Upload via Web Frontend](https://github.com/mi-classroom/mi-master-wt-beiboot-2025/issues/2)
* Date: 2025-05-05

Technical Story: Build a user-friendly frontend for image upload and metadata editing with file/folder structure support using Laravel as backend

## Context and Problem Statement

Need to create an easy-to-use frontend for image upload, metadata editing, and file/folder structure management. The frontend should integrate seamlessly with the Laravel backend while providing a modern development experience.

## Decision Drivers

* Developer experience
* Laravel integration quality
* Opportunity to expand technical knowledge beyond current main stack (React.js)
* Modern JavaScript framework requirements
* Community support and documentation quality

## Considered Options

* Vue.js with Inertia.js
* React.js with Inertia.js
* Livewire

## Decision Outcome

Chosen option: "Vue.js with Inertia.js", because it provides excellent Laravel integration, has a paradigm that was appealing to explore (reactive programming with proxies vs React's re-rendering approach), and offered an opportunity to expand skills beyond the current main stack.

### Positive Consequences

* Great community support with packages for common functionality
* Perfectly structured documentation allowing quick access to needed information
* Excellent Laravel integration through Inertia.js
* Reactive programming model using proxies for efficient UI updates
* Expanded technical knowledge beyond current React.js focus

### Negative Consequences

* Potential TypeScript integration issues in the future (historically not as strong as in React ecosystem, though this may have improved)

## Pros and Cons of the Options

### Vue.js with Inertia.js

* Good, because it uses proxies for reactive updates which is more efficient than React's re-rendering
* Good, because documentation is well-structured and easy to navigate
* Good, because it integrates seamlessly with Laravel through Inertia.js
* Good, because it has strong community support with useful packages
* Good, because it offers learning opportunities outside the usual React.js stack
* Bad, because TypeScript integration has historically been less mature than in React ecosystem

### React.js with Inertia.js

* Good, because it's the current main stack with existing expertise
* Good, because it has excellent TypeScript support
* Good, because it has the largest ecosystem of packages and tools
* Bad, because it uses re-rendering approach which can be less efficient
* Bad, because it doesn't offer the opportunity to learn new technology

### Livewire

* Good, because it offers very tight Laravel integration
* Bad, because of buggy UI behavior (components disappearing and showing as `<component wire:id="..." />`)
* Bad, because documentation is difficult to follow
* Bad, because finding the right solutions took excessive time

## Links

* Referenced by [Kernfunktion im Backend erstelle](https://github.com/mi-classroom/mi-master-wt-beiboot-2025/issues/1)
* Referenced by [Upload via Web Frontend](https://github.com/mi-classroom/mi-master-wt-beiboot-2025/issues/2)
* [Inertia.js Documentation](https://inertiajs.com/)
* [Vue.js Documentation](https://vuejs.org/)
* [Laravel Documentation](https://laravel.com/docs)
* [Livewire Documentation](https://laravel-livewire.com/docs)
