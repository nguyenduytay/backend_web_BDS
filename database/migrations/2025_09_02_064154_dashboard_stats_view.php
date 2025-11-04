<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        DB::statement("
            CREATE VIEW dashboard_stats_view AS
            SELECT
                (SELECT COUNT(*) FROM users) AS total_users,
                (SELECT COUNT(*) FROM properties) AS total_properties,
                (SELECT COUNT(*) FROM properties WHERE status = 'available') AS available_properties,
                (SELECT COUNT(*) FROM properties WHERE status = 'sold') AS sold_properties,
                (SELECT COUNT(*) FROM properties WHERE status = 'rented') AS rented_properties,
                (SELECT COUNT(*) FROM properties WHERE status = 'pending') AS pending_properties,
                (SELECT SUM(price) FROM properties WHERE status = 'sold') AS total_revenue,
                (SELECT COUNT(*) FROM property_types) AS total_property_types,
                (SELECT COUNT(*) FROM features) AS total_features,
                (SELECT COUNT(*) FROM contacts) AS total_contacts
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS dashboard_stats_view");
    }
};
