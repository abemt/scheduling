<style>
.container-fluid {
    padding: 15px;
}

.card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    margin-bottom: 1rem;
}

#calendar {
    background: #fff;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 8px;
    }

    .card {
        margin-bottom: 0.5rem;
        border-radius: 0;
    }

    #calendar {
        padding: 0.5rem;
    }

    .fc-toolbar {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 0.5rem !important;
    }

    .fc-toolbar-title {
        font-size: 1.1em !important;
    }

    .fc-button {
        padding: 0.2rem 0.4rem !important;
        font-size: 0.9rem !important;
    }

    .fc-view {
        font-size: 0.8rem;
    }
}
</style>

// ...existing code...
