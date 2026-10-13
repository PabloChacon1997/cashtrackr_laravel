import SubscriptionStatus from "@/Components/subscriptions/SubscriptionStatus";
import { Subscription } from "@/types/subscription";
import SubscriptionUpgrade from "@/Components/subscriptions/SubscriptionUpgrade";
import SubscriptionDowngrade from "@/Components/subscriptions/SubscriptionDowngrade";
import SubscriptionCancellation from "@/Components/subscriptions/SubscriptionCancellation";
import SubscriptionResume from "@/Components/subscriptions/SubscriptionResume";
import AppLayout from "@/layouts/AppLayout";

type Props = {
  subscription: Subscription
}

const statusColors = {
  green: 'bg-green-50 text-green-600 border-green-200',
  yellow: 'bg-yellow-50 text-yellow-600 border-yellow-200',
  orange: 'bg-orange-50 text-orange-600 border-orange-200',
  red: 'bg-red-50 text-red-600 border-red-200',
  gray: 'bg-gray-50 text-gray-700 border-gray-200',
};

export default function Manage({ subscription }: Props) {

  const title = "Administra tu suscripción";
  const isYearly = subscription.plan === 'yearly'

  
  

  return (
    <AppLayout title={title}>
      
      <main className="max-w-3xl mx-auto py-12 px-4">
        <h1 className="text-3xl font-black mb-12">{title}</h1>
        <p className="text-gray-500 mb-8 text-lg">
          Cambia tu Plan, Cancela o Reactiva tu Suscripción cuando quieras.
        </p>
        <SubscriptionStatus 
          isYearly={isYearly}
          price={subscription.price}
          status_label={subscription.status_label}
          color={statusColors[subscription.status_label.color]}
        />
        {
          subscription.on_grace_period 
            ?(<SubscriptionResume  ends_at={subscription.ends_at} />)
            :(
              <>
                { !isYearly && <SubscriptionUpgrade />  }
                {
                  isYearly && <SubscriptionDowngrade 
                      next_billing_date={subscription.next_billing_date}
                      ends_at={subscription.ends_at}
                    />
                }
                <SubscriptionCancellation next_billing_date={subscription.next_billing_date}/>
              </>
            )
        }
      </main>
    </AppLayout>
  )
}
