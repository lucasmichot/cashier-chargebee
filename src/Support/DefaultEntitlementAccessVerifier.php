<?php

namespace Chargebee\Cashier\Support;

use Chargebee\Cashier\Concerns\HasEntitlements;
use Chargebee\Cashier\Contracts\EntitlementAccessVerifier;
use Chargebee\Cashier\Entitlement;
use Chargebee\Cashier\Feature;
use Chargebee\Resources\Feature\Enums\Type as FeatureType;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

final class DefaultEntitlementAccessVerifier implements EntitlementAccessVerifier
{
    /**
     * Return true if the entitlements collectively provide all requested features.
     *
     * @param  Authenticatable&HasEntitlements  $user  The user to check access for
     * @param  Collection<Feature>  $features
     * @return bool
     */
    public static function hasAccessToFeatures($user, Collection $features): bool
    {
        $entitlements = $user->getEntitlements();
        $featureDefaults = config('cashier.entitlements.feature_defaults', []);

        // Every feature must be provided by at least one entitlement (AND logic)
        return $features->every(function ($feature) use ($entitlements, $featureDefaults) {
            return $entitlements->contains(function ($entitlement) use ($feature, $featureDefaults) {
                // For the default implementation, we can only check SWITCH feature types.
                // For the others, check if there is a fallback value configured.
                if ($entitlement->feature_id !== $feature->chargebee_id) {
                    return false;
                }

                $hasAccess = match (FeatureType::tryFromValue(strtolower($entitlement->feature_type))) {
                    FeatureType::SWITCH => $entitlement->value,
                    default => $featureDefaults[$feature->chargebee_id] ?? false,
                };

                // coerce the value to a boolean
                return filter_var($hasAccess, FILTER_VALIDATE_BOOLEAN);
            });
        });
    }
}
